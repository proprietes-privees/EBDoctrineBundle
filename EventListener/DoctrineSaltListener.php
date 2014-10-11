<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\SaltInterface;

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
            $args->setNewValue('salt', $this->generateSalt());
        }
    }

    /**
     * Generate salt
     *
     * @return string
     */
    private function generateSalt()
    {
        return hash('sha512', uniqid('encode', true) . time() . mt_rand(1, 999999999));
    }
}
