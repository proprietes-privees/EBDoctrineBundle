<?php

namespace EB\DoctrineBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class EBDoctrineExtension
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class EBDoctrineExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load configuration
        $conf = $this->processConfiguration(new Configuration(), $configs);

        // Filesystem
        $container->setParameter('eb_doctrine.filesystem.web_path', $conf['filesystem']['web_path']);
        $container->setParameter('eb_doctrine.filesystem.secured_path', $conf['filesystem']['secured_path']);
        $container->setParameter('eb_doctrine.filesystem.use_env_discriminator', $conf['filesystem']['use_env_discriminator']);
        $container->setParameter('eb_doctrine.filesystem.use_class_discriminator', $conf['filesystem']['use_class_discriminator']);
        $container->setParameter('eb_doctrine.filesystem.depth', $conf['filesystem']['depth']);

        // Loggable
        $container->setParameter('eb_doctrine.loggable.persisted', $conf['loggable']['persisted']);
        $container->setParameter('eb_doctrine.loggable.updated', $conf['loggable']['updated']);
        $container->setParameter('eb_doctrine.loggable.removed', $conf['loggable']['removed']);

        // Load services
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('event_listener.xml');
        $loader->load('paginator.xml');
    }
}
