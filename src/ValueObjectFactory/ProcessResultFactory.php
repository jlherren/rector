<?php

declare (strict_types=1);
namespace Rector\Core\ValueObjectFactory;

use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\ProcessResult;
use Rector\PostRector\Collector\NodesToRemoveCollector;
final class ProcessResultFactory
{
    /**
     * @var RemovedAndAddedFilesCollector
     */
    private $removedAndAddedFilesCollector;
    /**
     * @var NodesToRemoveCollector
     */
    private $nodesToRemoveCollector;
    public function __construct(RemovedAndAddedFilesCollector $removedAndAddedFilesCollector, NodesToRemoveCollector $nodesToRemoveCollector)
    {
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
        $this->nodesToRemoveCollector = $nodesToRemoveCollector;
    }
    /**
     * @param File[] $files
     */
    public function create(array $files) : ProcessResult
    {
        $fileDiffs = [];
        $errors = [];
        foreach ($files as $file) {
            if ($file->getFileDiff() === null) {
                continue;
            }
            $errors = \array_merge($errors, $file->getErrors());
            $fileDiffs[] = $file->getFileDiff();
        }
        return new ProcessResult($fileDiffs, $errors, $this->removedAndAddedFilesCollector->getAddedFileCount(), $this->removedAndAddedFilesCollector->getRemovedFilesCount(), $this->nodesToRemoveCollector->getCount());
    }
}
