<?php

namespace RectorPrefix20211024\TYPO3\CMS\Core\Resource\Exception;

use Exception;
if (\class_exists('TYPO3\\CMS\\Core\\Resource\\Exception\\InvalidFileNameException')) {
    return;
}
class InvalidFileNameException extends \Exception
{
}
