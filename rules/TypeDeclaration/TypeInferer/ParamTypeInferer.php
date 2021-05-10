<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration\TypeInferer;

use PhpParser\Node\Param;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\TypeDeclaration\Contract\TypeInferer\ParamTypeInfererInterface;
use Rector\TypeDeclaration\TypeAnalyzer\GenericClassStringTypeNormalizer;
final class ParamTypeInferer
{
    /**
     * @var ParamTypeInfererInterface[]
     */
    private $paramTypeInferers = [];
    /**
     * @var GenericClassStringTypeNormalizer
     */
    private $genericClassStringTypeNormalizer;
    /**
     * @param ParamTypeInfererInterface[] $paramTypeInferers
     */
    public function __construct(GenericClassStringTypeNormalizer $genericClassStringTypeNormalizer, array $paramTypeInferers)
    {
        $this->paramTypeInferers = $paramTypeInferers;
        $this->genericClassStringTypeNormalizer = $genericClassStringTypeNormalizer;
    }
    public function inferParam(Param $param) : Type
    {
        foreach ($this->paramTypeInferers as $paramTypeInferer) {
            $paramType = $paramTypeInferer->inferParam($param);
            if ($paramType instanceof MixedType) {
                continue;
            }
            return $this->genericClassStringTypeNormalizer->normalize($paramType);
        }
        return new MixedType();
    }
}
