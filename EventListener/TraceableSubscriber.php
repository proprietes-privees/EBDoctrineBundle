<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs;
use Doctrine\ORM\Tools\ToolEvents;

/**
 * Class TraceableSubscriber
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class TraceableSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    function getSubscribedEvents()
    {
        return array(
            ToolEvents::postGenerateSchemaTable,
            ToolEvents::postGenerateSchema,
        );
    }

    /**
     * @param GenerateSchemaTableEventArgs $args
     */
    public function postGenerateSchemaTable(GenerateSchemaTableEventArgs $args)
    {
        $schema = $args->getSchema();
        $entityTable = $args->getClassTable();
        $revisionTable = $schema->createTable($entityTable->getName() . '_trace');
        foreach ($entityTable->getColumns() as $column) {
            $revisionTable->addColumn(
                $column->getName(),
                $column->getType()->getName(),
                array_merge(
                    $column->toArray(),
                    array(
                        'notnull' => false,
                        'autoincrement' => false,
                    )
                )
            );
        }
        $revisionTable->addColumn('rev', 'integer');
        $revisionTable->addColumn('rev_type', 'string', array('length' => 4));
        $pkColumns = $entityTable->getPrimaryKey()->getColumns();
        $pkColumns[] = $this->config->getRevisionFieldName();
        $revisionTable->setPrimaryKey($pkColumns);
    }

    /**
     * @param GenerateSchemaEventArgs $args
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args)
    {
        // Schema
        $schema = $args->getSchema();

        $revisionsTable = $schema->createTable($args->getSchema()->getName() . '_trace');
        $revisionsTable->addColumn('id', $this->config->getRevisionIdFieldType(), array(
            'autoincrement' => true,
        ));
        $revisionsTable->addColumn('timestamp', 'datetime');
        $revisionsTable->addColumn('username', 'string');
        $revisionsTable->setPrimaryKey(array('id'));
    }
}
