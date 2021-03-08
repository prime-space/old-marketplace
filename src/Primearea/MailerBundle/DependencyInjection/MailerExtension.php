<?php namespace Primearea\MailerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * {@inheritdoc}
 */
class MailerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('mailer.host', $config['host']);
        $container->setParameter('mailer.port', $config['port']);
        $container->setParameter('mailer.secure', $config['secure']);
        $container->setParameter('mailer.user', $config['user']);
        $container->setParameter('mailer.pass', $config['pass']);
        $container->setParameter('mailer.smtp_auth', $config['smtp_auth']);
        $container->setParameter('mailer.sender.email', $config['sender']['email']);
        $container->setParameter('mailer.sender.name', $config['sender']['name']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('service.yml');
    }
}
