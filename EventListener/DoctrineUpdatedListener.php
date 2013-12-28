<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\UpdatedInterface;

/**
 * Class DoctrineUpdatedListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineUpdatedListener
{
    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof UpdatedInterface) {
            $entity->setUpdated(new \DateTime());

            // Save new value
            $mdt = $args->getEntityManager()->getClassMetadata(get_class($entity));
            $args->getEntityManager()->getUnitOfWork()->recomputeSingleEntityChangeSet($mdt, $entity);
        }
    }
}
