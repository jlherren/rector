<?php

declare (strict_types=1);
namespace RectorPrefix20210510;

use Rector\Core\Configuration\Option;
use Rector\NetteToSymfony\Rector\Class_\RenameTesterTestToPHPUnitToTestFileRector;
use RectorPrefix20210510\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $containerConfigurator->import(__DIR__ . '/../../../../../config/config.php');
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::FILE_EXTENSIONS, ['php', 'phpt']);
    $services = $containerConfigurator->services();
    $services->set(RenameTesterTestToPHPUnitToTestFileRector::class);
};
