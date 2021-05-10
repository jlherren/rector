<?php

declare (strict_types=1);
namespace Rector\NodeTypeResolver\PhpDocNodeVisitor;

use RectorPrefix20210510\Nette\Utils\Strings;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\ObjectType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Renaming\ValueObject\PseudoNamespaceToNamespace;
use Rector\StaticTypeMapper\StaticTypeMapper;
use RectorPrefix20210510\Symplify\SimplePhpDocParser\PhpDocNodeVisitor\AbstractPhpDocNodeVisitor;
final class UnderscoreRenamePhpDocNodeVisitor extends AbstractPhpDocNodeVisitor
{
    /**
     * @var StaticTypeMapper
     */
    private $staticTypeMapper;
    /**
     * @var PseudoNamespaceToNamespace|null
     */
    private $pseudoNamespaceToNamespace;
    /**
     * @var \PhpParser\Node|null
     */
    private $currentPhpParserNode;
    public function __construct(StaticTypeMapper $staticTypeMapper)
    {
        $this->staticTypeMapper = $staticTypeMapper;
    }
    public function beforeTraverse(Node $node) : void
    {
        if ($this->pseudoNamespaceToNamespace === null) {
            throw new ShouldNotHappenException('Set PseudoNamespaceToNamespace first');
        }
        if (!$this->currentPhpParserNode instanceof \PhpParser\Node) {
            throw new ShouldNotHappenException('Set "$currentPhpParserNode" first');
        }
    }
    public function enterNode(Node $node) : ?Node
    {
        if (!$node instanceof IdentifierTypeNode) {
            return null;
        }
        if ($this->shouldSkip($node, $this->currentPhpParserNode, $this->pseudoNamespaceToNamespace)) {
            return null;
        }
        /** @var IdentifierTypeNode $node */
        $staticType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($node, $this->currentPhpParserNode);
        if (!$staticType instanceof ObjectType) {
            return null;
        }
        // change underscore to \\
        $slashedName = '\\' . Strings::replace($staticType->getClassName(), '#_#', '\\');
        return new IdentifierTypeNode($slashedName);
    }
    public function setPseudoNamespaceToNamespace(PseudoNamespaceToNamespace $pseudoNamespaceToNamespace) : void
    {
        $this->pseudoNamespaceToNamespace = $pseudoNamespaceToNamespace;
    }
    public function setCurrentPhpParserNode(\PhpParser\Node $node) : void
    {
        $this->currentPhpParserNode = $node;
    }
    private function shouldSkip(IdentifierTypeNode $identifierTypeNode, \PhpParser\Node $phpParserNode, PseudoNamespaceToNamespace $pseudoNamespaceToNamespace) : bool
    {
        $staticType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($identifierTypeNode, $phpParserNode);
        if (!$staticType instanceof ObjectType) {
            return \true;
        }
        if (!Strings::startsWith($staticType->getClassName(), $pseudoNamespaceToNamespace->getNamespacePrefix())) {
            return \true;
        }
        // excluded?
        return \in_array($staticType->getClassName(), $pseudoNamespaceToNamespace->getExcludedClasses(), \true);
    }
}
