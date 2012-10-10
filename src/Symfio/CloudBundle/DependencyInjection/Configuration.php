<?php

namespace Symfio\CloudBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    protected $cacheDir;

    public function __construct($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder $builder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $rootNode = $builder->root('symfio_cloud');

        $rootNode
            ->children()
            ->scalarNode('default')->defaultValue('amazon')->end()
            ->arrayNode('amazon')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('credentials')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('key')->isRequired()->end()
                            ->scalarNode('secret')->isRequired()->end()
                            ->scalarNode('account_id')->defaultValue(null)->end()
                            ->scalarNode('canonical_id')->defaultValue(null)->end()
                            ->scalarNode('canonical_name')->defaultValue(null)->end()
                            ->scalarNode('default_cache_config')->defaultValue($this->cacheDir)->end()
                            ->arrayNode('enable_extensions')
                                ->defaultValue(array())
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->booleanNode('certificate_authority')->defaultFalse()->end()
                        ->end()
                    ->end()
                    ->arrayNode('images')
                        ->isRequired()
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('id')->isRequired()->end()
                                ->scalarNode('instance_type')->defaultValue(\AmazonEC2::INSTANCE_MICRO)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}