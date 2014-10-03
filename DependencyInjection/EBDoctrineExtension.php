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
        $container->setParameter('eb_doctrine.path.web', $conf['path']['web']);
        $container->setParameter('eb_doctrine.path.secured', $conf['path']['secured']);
        $container->setParameter('eb_doctrine.useEnvDiscriminator', $conf['useEnvDiscriminator']);
        $container->setParameter('eb_doctrine.useClassDiscriminator', $conf['useClassDiscriminator']);
        $container->setParameter('eb_doctrine.depth', $conf['depth']);

        // Load services
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('event_listener.xml');
        $loader->load('paginator.xml');
    }
}
