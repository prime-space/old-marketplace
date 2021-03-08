<?php namespace Primearea\PrimeareaBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('primearea');

        $rootNode
            ->children()
                ->scalarNode('app_path')->isRequired()->cannotBeEmpty()->end()
        ;

        return $treeBuilder;
    }
}
