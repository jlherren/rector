<?php

declare (strict_types=1);
namespace Rector\VendorLocker\NodeVendorLocker;

use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Type\MixedType;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\VendorLocker\Reflection\MethodReflectionContractAnalyzer;
final class ClassMethodReturnVendorLockResolver
{
    /**
     * @var MethodReflectionContractAnalyzer
     */
    private $methodReflectionContractAnalyzer;
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    public function __construct(MethodReflectionContractAnalyzer $methodReflectionContractAnalyzer, NodeNameResolver $nodeNameResolver)
    {
        $this->methodReflectionContractAnalyzer = $methodReflectionContractAnalyzer;
        $this->nodeNameResolver = $nodeNameResolver;
    }
    public function isVendorLocked(ClassMethod $classMethod) : bool
    {
        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);
        if (!$scope instanceof Scope) {
            return \false;
        }
        $classReflection = $scope->getClassReflection();
        if (!$classReflection instanceof ClassReflection) {
            return \false;
        }
        if (\count($classReflection->getAncestors()) === 1) {
            return \false;
        }
        $methodName = $this->nodeNameResolver->getName($classMethod);
        if ($this->isVendorLockedByParentClass($classReflection, $methodName)) {
            return \true;
        }
        if ($classReflection->isTrait()) {
            return \false;
        }
        return $this->methodReflectionContractAnalyzer->hasInterfaceContract($classReflection, $methodName);
    }
    private function isVendorLockedByParentClass(ClassReflection $classReflection, string $methodName) : bool
    {
        foreach ($classReflection->getParents() as $parentClassReflections) {
            if (!$parentClassReflections->hasMethod($methodName)) {
                continue;
            }
            $parentClassMethodReflection = $parentClassReflections->getNativeMethod($methodName);
            $parametersAcceptor = $parentClassMethodReflection->getVariants()[0];
            if (!$parametersAcceptor instanceof FunctionVariantWithPhpDocs) {
                continue;
            }
            // here we count only on strict types, not on docs
            return !$parametersAcceptor->getNativeReturnType() instanceof MixedType;
        }
        return \false;
    }
}
