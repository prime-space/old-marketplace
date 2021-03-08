<?php namespace Primearea\MailerBundle\DependencyInjection;

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

        $rootNode = $treeBuilder->root('mailer');
        $rootNode
            ->children()
                ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('port')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('secure')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('user')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('pass')->isRequired()->cannotBeEmpty()->end()
                ->booleanNode('smtp_auth')->isRequired()->defaultFalse()->end()
                ->arrayNode('sender')->isRequired()
                    ->children()
                        ->scalarNode('email')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('name')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
