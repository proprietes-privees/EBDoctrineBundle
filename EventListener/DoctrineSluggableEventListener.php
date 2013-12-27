<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\SluggableInterface;
use EB\StringBundle\Twig\Extension\StringExtension;

/**
 * Class DoctrineSluggableEventListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineSluggableEventListener
{
    /**
     * @var StringExtension
     */
    private $string;

    /**
     * @param StringExtension $string
     */
    public function __construct(StringExtension $string)
    {
        $this->string = $string;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SluggableInterface) {
            $entity->setSlug($this->string->search(implode(' ', $entity->getSluggableData())));
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SluggableInterface) {
            $entity->setSlug($this->string->search(implode(' ', $entity->getSluggableData())));

            // Save new value
            $mdt = $args->getEntityManager()->getClassMetadata(get_class($entity));
            $args->getEntityManager()->getUnitOfWork()->recomputeSingleEntityChangeSet($mdt, $entity);
        }
    }
}
