<?php

declare (strict_types=1);
namespace Rector\Naming\PropertyRenamer;

use PhpParser\Node\Stmt\Property;
use Rector\Naming\Guard\PropertyConflictingNameGuard\BoolPropertyConflictingNameGuard;
use Rector\Naming\ValueObject\PropertyRename;
final class BoolPropertyRenamer
{
    /**
     * @var BoolPropertyConflictingNameGuard
     */
    private $boolPropertyConflictingNameGuard;
    /**
     * @var PropertyRenamer
     */
    private $propertyRenamer;
    public function __construct(BoolPropertyConflictingNameGuard $boolPropertyConflictingNameGuard, \Rector\Naming\PropertyRenamer\PropertyRenamer $propertyRenamer)
    {
        $this->boolPropertyConflictingNameGuard = $boolPropertyConflictingNameGuard;
        $this->propertyRenamer = $propertyRenamer;
    }
    public function rename(PropertyRename $propertyRename) : ?Property
    {
        if ($this->boolPropertyConflictingNameGuard->isConflicting($propertyRename)) {
            return null;
        }
        return $this->propertyRenamer->rename($propertyRename);
    }
}
