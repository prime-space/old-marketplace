<?php namespace Primearea\PrimeareaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * {@inheritdoc}
 */
class PrimeareaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('command.yml');
        $loader->load('repository.yml');
        $loader->load('store.yml');
        $loader->load('service.yml');
        $loader->load('aggregator.yml');
        $loader->load('logger.yml');
        $loader->load('controller.yml');

        $container->setParameter('primearea.app_path', $config['app_path']);
    }
}
