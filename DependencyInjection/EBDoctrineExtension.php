<?php

namespace EB\DoctrineBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Process\Process;

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
        $conf = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('eb_doctrine.pager.pager.page_name', $conf['page_name']);
        $container->setParameter('eb_doctrine.pager.pager.limit_name', $conf['limit_name']);
        $container->setParameter('eb_doctrine.pager.pager.order_name', $conf['order_name']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
