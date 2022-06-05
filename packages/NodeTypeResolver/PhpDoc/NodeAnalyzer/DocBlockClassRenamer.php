<?php

declare (strict_types=1);
namespace Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer;

use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\NodeTypeResolver\PhpDocNodeVisitor\ClassRenamePhpDocNodeVisitor;
use Rector\NodeTypeResolver\ValueObject\OldToNewType;
use RectorPrefix20220605\Symplify\Astral\PhpDocParser\PhpDocNodeTraverser;
final class DocBlockClassRenamer
{
    /**
     * @readonly
     * @var \Rector\NodeTypeResolver\PhpDocNodeVisitor\ClassRenamePhpDocNodeVisitor
     */
    private $classRenamePhpDocNodeVisitor;
    public function __construct(\Rector\NodeTypeResolver\PhpDocNodeVisitor\ClassRenamePhpDocNodeVisitor $classRenamePhpDocNodeVisitor)
    {
        $this->classRenamePhpDocNodeVisitor = $classRenamePhpDocNodeVisitor;
    }
    /**
     * @param OldToNewType[] $oldToNewTypes
     */
    public function renamePhpDocType(\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo $phpDocInfo, array $oldToNewTypes) : void
    {
        if ($oldToNewTypes === []) {
            return;
        }
        $phpDocNodeTraverser = new \RectorPrefix20220605\Symplify\Astral\PhpDocParser\PhpDocNodeTraverser();
        $phpDocNodeTraverser->addPhpDocNodeVisitor($this->classRenamePhpDocNodeVisitor);
        $this->classRenamePhpDocNodeVisitor->setOldToNewTypes($oldToNewTypes);
        $phpDocNodeTraverser->traverse($phpDocInfo->getPhpDocNode());
    }
}
