<?php

namespace EB\DoctrineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
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

        $this->addFilesystemConfiguration($ch);
        $this->addLoggableConfiguration($ch);
        $this->addPaginatorConfiguration($ch);

        return $tb;
    }

    /**
     * Add filesystem configuration
     *
     * @param NodeBuilder $node
     */
    private function addFilesystemConfiguration(NodeBuilder $node)
    {
        $fs = $node->arrayNode('filesystem')->addDefaultsIfNotSet()->children();
        $fs->scalarNode('web_path')->defaultValue('/files')->info('Web file path.')->example('/files');
        $fs->scalarNode('secured_path')->defaultValue('%kernel.root_dir%/cache/%kernel.environment%/files')->info('Secured file path.')->example('/var/my-data');
        $fs->booleanNode('use_env_discriminator')->defaultTrue()->info('Wether env is used in paths')->example('true');
        $fs->booleanNode('use_class_discriminator')->defaultTrue()->info('Wether class is used in paths')->example('true');
        $fs->integerNode('depth')->defaultValue(0)->info('File tree depth')->example('5');
    }

    /**
     * Add loggable configuration
     *
     * @param NodeBuilder $node
     */
    private function addLoggableConfiguration(NodeBuilder $node)
    {
        $loggable = $node->arrayNode('loggable')->addDefaultsIfNotSet()->children();
        $loggable->scalarNode('persisted')->defaultValue('L\'élément %%entity%% a été créé avec succès !')->info('Persisted message or translation key.')->example('L\'élément %%entity%% a été créé avec succès !');
        $loggable->scalarNode('updated')->defaultValue('L\'élément %%entity%% a été modifié avec succès !')->info('Updated message or translation key.')->example('L\'élément %%entity%% a été modifié avec succès !');
        $loggable->scalarNode('removed')->defaultValue('L\'élément %%entity%% a été supprimé avec succès !')->info('Removed message or translation key.')->example('L\'élément %%entity%% a été supprimé avec succès !');
    }

    /**
     * Add paginator configuration
     *
     * @param NodeBuilder $node
     */
    private function addPaginatorConfiguration(NodeBuilder $node)
    {
        $paginator = $node->arrayNode('paginator')->addDefaultsIfNotSet()->children();
        $paginator->integerNode('default_limit')->defaultNull()->info('Default paginator limit')->example('10');
        $paginator->integerNode('max_limit')->defaultNull()->info('Maximum paginator limit')->example('100');
        $paginator->booleanNode('use_output_walker')->defaultFalse()->info('Wether we have to use the output walker')->example('false');
    }
}
