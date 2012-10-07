<?php

namespace Symfio\CloudBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfio\CloudBundle\DependencyInjection\Compiler\CloudPass;

class SymfioCloudBundle extends Bundle
{
    public function build(ContainerBuilder $container) 
    {
        parent::build($container);

        $container->addCompilerPass(new CloudPass());
    }
}
