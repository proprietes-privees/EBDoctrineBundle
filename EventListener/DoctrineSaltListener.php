<?php

namespace EB\UserBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use EB\UserBundle\Entity\SaltInterface;

/**
 * Class DoctrineSaltListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineSaltListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SaltInterface) {
            $entity->setSalt(hash('sha512', uniqid('salt', true) . time() . mt_rand(1, 999999999)));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SaltInterface) {
            $entity->setSalt(hash('sha512', uniqid('salt', true) . time() . mt_rand(1, 999999999)));
        }
    }
}
