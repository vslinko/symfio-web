<?php

namespace Symfio\CloudBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

class SymfioCloudExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration($container->getParameter('kernel.cache_dir')), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/'));
        $loader->load('services.yml');

        $container->getDefinition('symfio.cloud.amazon.image_factory')
            ->addArgument($config['amazon']['images']);

        $container->getDefinition('symfio.cloud.amazon')
            ->addArgument($config['amazon']['credentials'])
            ->addArgument($container->getDefinition('symfio.cloud.amazon.image_factory'))
        ;

        $container->getDefinition('symfio.cloud.manager')
            ->addArgument($config['default'])
        ;
    }
}
