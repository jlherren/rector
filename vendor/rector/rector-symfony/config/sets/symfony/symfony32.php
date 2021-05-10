<?php

declare (strict_types=1);
namespace RectorPrefix20210510;

use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Arguments\ValueObject\ArgumentAdder;
use RectorPrefix20210510\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
return static function (ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(ArgumentAdderRector::class)->call('configure', [[ArgumentAdderRector::ADDED_ARGUMENTS => ValueObjectInliner::inline([new ArgumentAdder('Symfony\\Component\\DependencyInjection\\ContainerBuilder', 'addCompilerPass', 2, 'priority', 0)])]]);
};
