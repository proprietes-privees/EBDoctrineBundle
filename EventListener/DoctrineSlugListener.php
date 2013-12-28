<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\SlugInterface;
use EB\StringBundle\String\StringService;

/**
 * Class DoctrineSlugListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineSlugListener
{
    /**
     * @var StringService
     */
    private $string;

    /**
     * @param StringService $string
     */
    public function __construct(StringService $string)
    {
        $this->string = $string;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SlugInterface) {
            $entity->setSlug($this->string->search($entity->getStringToSlug()));
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SlugInterface) {
            $entity->setSlug($this->string->search($entity->getStringToSlug()));

            // Save new value
            $mdt = $args->getEntityManager()->getClassMetadata(get_class($entity));
            $args->getEntityManager()->getUnitOfWork()->recomputeSingleEntityChangeSet($mdt, $entity);
        }
    }
}
