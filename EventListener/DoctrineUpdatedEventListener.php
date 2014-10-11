<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\UpdatedInterface;

/**
 * Class DoctrineUpdatedEventListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineUpdatedEventListener
{
    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof UpdatedInterface) {
            $entity->setUpdated(new \DateTime());
        }
    }
}
