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
        $ch = $tb->root('eb_doctrine')->children();
        $ch->scalarNode('page_name')->defaultValue('page')->cannotBeEmpty();
        $ch->scalarNode('limit_name')->defaultValue('limit')->cannotBeEmpty();
        $ch->scalarNode('order_name')->defaultValue('order')->cannotBeEmpty();

        return $tb;
    }
}
