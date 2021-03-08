<?php namespace Well\DBBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * {@inheritdoc}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('well_db');

        $rootNode
            ->fixXmlConfig('shard')
            ->fixXmlConfig('connection')
            ->children()
                ->arrayNode('connections')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('port')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('database')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('username')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('password')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('charset')->cannotBeEmpty()->defaultValue('utf8mb4')->end()
                            ->arrayNode('options')
                                ->prototype('integer')
                                    ->defaultValue([])
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('shards')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('port')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('database')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('username')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('password')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('charset')->cannotBeEmpty()->defaultValue('utf8mb4')->end()
                                ->arrayNode('options')
                                    ->prototype('integer')
                                        ->defaultValue([])
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('logger')
                    ->children()
                        ->scalarNode('id')->isRequired()->defaultNull()->end()
                        ->scalarNode('channel')->isRequired()->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('pre_queries')
                    ->prototype('scalar')
                        ->defaultValue([])
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
