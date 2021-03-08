<?php namespace Primearea\PrimeareaBundle\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AggregatorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('primearea.aggregator.aggregator_provider');

        $taggedServices = $container->findTaggedServiceIds('aggregator');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addAggregator', [
                    new Reference($id),
                    $attributes['aggregator']
                ]);
            }
        }
    }
}
