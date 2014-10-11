<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\SaltInterface;
use EB\DoctrineBundle\Salt\SaltTrait;

/**
 * Class DoctrineSaltEventListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineSaltEventListener
{
    use SaltTrait;

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SaltInterface) {
            $entity->setSalt($this->generateSalt());
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SaltInterface) {
            $entity->setSalt($this->generateSalt());
        }
    }
}
