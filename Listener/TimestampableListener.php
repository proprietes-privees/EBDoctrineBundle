<?php

namespace EB\DoctrineBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use EB\DoctrineBundle\Entity\TimestampableInterface;

/**
 * Class TimestampableListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class TimestampableListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof TimestampableInterface) {
            $entity->setCreated(new \DateTime());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof TimestampableInterface) {
            $entity->setUpdated(new \DateTime());
        }
    }
}
