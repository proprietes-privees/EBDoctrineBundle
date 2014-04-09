<?php

namespace EB\DoctrineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();

        $ch = $tb->root('eb_doctrine')->addDefaultsIfNotSet()->children();
        $ch->booleanNode('useEnvDiscriminator')->defaultTrue()->info('Wether env is used in paths')->example('true');
        $ch->booleanNode('useClassDiscriminator')->defaultTrue()->info('Wether class is used in paths')->example('true');
        $ch->integerNode('depth')->defaultValue(0)->info('File tree depth')->example('5');
        $pa = $ch->arrayNode('path')->addDefaultsIfNotSet()->children();
        $pa->scalarNode('web')->defaultValue('/files')->info('Web file path.')->example('/files');
        $pa->scalarNode('secured')->defaultValue('%kernel.root_dir%/cache/%kernel.environment%/files')->info('Secured file path.')->example('/var/my-data');

        return $tb;
    }
}
