<?php

declare (strict_types=1);
namespace RectorPrefix20210510\Symplify\ComposerJsonManipulator\Bundle;

use RectorPrefix20210510\Symfony\Component\HttpKernel\Bundle\Bundle;
use RectorPrefix20210510\Symplify\ComposerJsonManipulator\DependencyInjection\Extension\ComposerJsonManipulatorExtension;
final class ComposerJsonManipulatorBundle extends Bundle
{
    protected function createContainerExtension() : ?\RectorPrefix20210510\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new ComposerJsonManipulatorExtension();
    }
}
