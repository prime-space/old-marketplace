<?php namespace Primearea\PrimeareaBundle\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ExecutorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('primearea.transaction.executor_provider');

        $taggedServices = $container->findTaggedServiceIds('transaction_executor');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addExecutor', [
                    new Reference($id),
                    $attributes['type']
                ]);
            }
        }
    }
}
