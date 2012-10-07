<?php

namespace Symfio\CloudBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class CloudPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('symfio.cloud.manager')) {
            return;
        }

        $cloudManager = $container->getDefinition('symfio.cloud.manager');

        foreach ($container->findTaggedServiceIds('symfio.cloud') as $id => $attributes) {
            $cloudManager->addMethodCall('add', array(new Reference($id)));
        }
    }
}
