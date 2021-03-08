<?php namespace Well\DBBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Well\DBBundle\DB\Client;
use Well\DBBundle\DB\ClientProvider;

/**
 * {@inheritdoc}
 */
class WellDBExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $logger_channel = $config['logger']['channel'];
        $logger = (null === $config['logger']['id'])
            ? null
            : new Reference($config['logger']['id']);

        $clientProvider = $container->register('well.db.client_provider', ClientProvider::class);

        foreach ($config['connections'] as $name => $options) {
            $service = $container
                ->register(sprintf('well.db.client.%1$s', $name), Client::class)
                ->addArgument($options['host'])
                ->addArgument($options['port'])
                ->addArgument($options['database'])
                ->addArgument($options['username'])
                ->addArgument($options['password'])
                ->addArgument($options['charset'])
                ->addArgument($config['pre_queries'])
                ->addArgument($options['options'])
                ->addArgument($logger);

            $clientProvider->addMethodCall('addClient', [$service]);

            if (null !== $logger_channel) {
                $service->addTag('monolog.logger', ['channel' => $logger_channel]);
            }
        }

        foreach ($config['shards'] as $type => $connections) {
            foreach ($connections as $shard_id => $options) {
                $service = $container
                    ->register(sprintf('well.db.shard.%1$s.%2$s', $type, $shard_id), Client::class)
                    ->addArgument($options['host'])
                    ->addArgument($options['port'])
                    ->addArgument($options['database'])
                    ->addArgument($options['username'])
                    ->addArgument($options['password'])
                    ->addArgument($options['charset'])
                    ->addArgument($options['options'])
                    ->addArgument($logger);

                if (null !== $logger_channel) {
                    $service->addTag('monolog.logger', ['channel' => $logger_channel]);
                }
            }
        }
    }
}
