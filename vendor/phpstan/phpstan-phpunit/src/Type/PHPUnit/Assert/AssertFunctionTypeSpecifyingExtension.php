<?php

declare (strict_types=1);
namespace PHPStan\Type\PHPUnit\Assert;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\FunctionTypeSpecifyingExtension;
class AssertFunctionTypeSpecifyingExtension implements FunctionTypeSpecifyingExtension, TypeSpecifierAwareExtension
{
    /** @var TypeSpecifier */
    private $typeSpecifier;
    public function setTypeSpecifier(TypeSpecifier $typeSpecifier) : void
    {
        $this->typeSpecifier = $typeSpecifier;
    }
    public function isFunctionSupported(FunctionReflection $functionReflection, FuncCall $node, TypeSpecifierContext $context) : bool
    {
        return \PHPStan\Type\PHPUnit\Assert\AssertTypeSpecifyingExtensionHelper::isSupported($functionReflection->getName(), $node->args);
    }
    public function specifyTypes(FunctionReflection $functionReflection, FuncCall $node, Scope $scope, TypeSpecifierContext $context) : SpecifiedTypes
    {
        return \PHPStan\Type\PHPUnit\Assert\AssertTypeSpecifyingExtensionHelper::specifyTypes($this->typeSpecifier, $scope, $functionReflection->getName(), $node->args);
    }
}
