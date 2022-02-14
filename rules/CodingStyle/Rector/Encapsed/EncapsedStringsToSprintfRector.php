<?php

declare (strict_types=1);
namespace Rector\CodingStyle\Rector\Encapsed;

use RectorPrefix20220214\Nette\Utils\Strings;
use const PHP_EOL;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\EncapsedStringPart;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\Type;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\EncapsedStringsToSprintfRectorTest
 */
final class EncapsedStringsToSprintfRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var array<string, array<class-string<Type>>>
     */
    private const FORMAT_SPECIFIERS = ['%s' => ['PHPStan\\Type\\StringType'], '%d' => ['PHPStan\\Type\\Constant\\ConstantIntegerType', 'PHPStan\\Type\\IntegerRangeType', 'PHPStan\\Type\\IntegerType']];
    /**
     * @var string
     */
    private $sprintfFormat = '';
    /**
     * @var Expr[]
     */
    private $argumentVariables = [];
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Convert enscaped {$string} to more readable sprintf', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(string $format)
    {
        return "Unsupported format {$format}";
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(string $format)
    {
        return sprintf('Unsupported format %s', $format);
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Scalar\Encapsed::class];
    }
    /**
     * @param Encapsed $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $this->sprintfFormat = '';
        $this->argumentVariables = [];
        foreach ($node->parts as $part) {
            if ($part instanceof \PhpParser\Node\Scalar\EncapsedStringPart) {
                $this->collectEncapsedStringPart($part);
            } else {
                $this->collectExpr($part);
            }
        }
        return $this->createSprintfFuncCallOrConcat($this->sprintfFormat, $this->argumentVariables);
    }
    private function collectEncapsedStringPart(\PhpParser\Node\Scalar\EncapsedStringPart $encapsedStringPart) : void
    {
        $stringValue = $encapsedStringPart->value;
        if ($stringValue === "\n") {
            $this->argumentVariables[] = new \PhpParser\Node\Expr\ConstFetch(new \PhpParser\Node\Name('PHP_EOL'));
            $this->sprintfFormat .= '%s';
            return;
        }
        $this->sprintfFormat .= \RectorPrefix20220214\Nette\Utils\Strings::replace($stringValue, '#%#', '%%');
    }
    private function collectExpr(\PhpParser\Node\Expr $expr) : void
    {
        $type = $this->nodeTypeResolver->getType($expr);
        $found = \false;
        foreach (self::FORMAT_SPECIFIERS as $key => $types) {
            if (\in_array(\get_class($type), $types, \true)) {
                $this->sprintfFormat .= $key;
                $found = \true;
                break;
            }
        }
        if (!$found) {
            $this->sprintfFormat .= '%s';
        }
        // remove: ${wrap} → $wrap
        if ($expr instanceof \PhpParser\Node\Expr\Variable) {
            $expr->setAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE, null);
        }
        $this->argumentVariables[] = $expr;
    }
    /**
     * @param Expr[] $argumentVariables
     * @return Concat|FuncCall|null
     */
    private function createSprintfFuncCallOrConcat(string $string, array $argumentVariables) : ?\PhpParser\Node
    {
        // special case for variable with PHP_EOL
        if ($string === '%s%s' && \count($argumentVariables) === 2 && $this->hasEndOfLine($argumentVariables)) {
            return new \PhpParser\Node\Expr\BinaryOp\Concat($argumentVariables[0], $argumentVariables[1]);
        }
        // checks for windows or linux line ending. \n is contained in both.
        if (\strpos($string, "\n") !== \false) {
            return null;
        }
        $arguments = [new \PhpParser\Node\Arg(new \PhpParser\Node\Scalar\String_($string))];
        foreach ($argumentVariables as $argumentVariable) {
            $arguments[] = new \PhpParser\Node\Arg($argumentVariable);
        }
        return new \PhpParser\Node\Expr\FuncCall(new \PhpParser\Node\Name('sprintf'), $arguments);
    }
    /**
     * @param Expr[] $argumentVariables
     */
    private function hasEndOfLine(array $argumentVariables) : bool
    {
        foreach ($argumentVariables as $argumentVariable) {
            if (!$argumentVariable instanceof \PhpParser\Node\Expr\ConstFetch) {
                continue;
            }
            if ($this->isName($argumentVariable, 'PHP_EOL')) {
                return \true;
            }
        }
        return \false;
    }
}
