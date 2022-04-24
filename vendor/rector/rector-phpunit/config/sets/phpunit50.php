<?php

declare (strict_types=1);
namespace RectorPrefix20220424;

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Rector\StaticCall\GetMockRector;
return static function (\Rector\Config\RectorConfig $rectorConfig) : void {
    $rectorConfig->rule(\Rector\PHPUnit\Rector\StaticCall\GetMockRector::class);
};
