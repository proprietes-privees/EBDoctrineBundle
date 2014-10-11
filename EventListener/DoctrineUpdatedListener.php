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
            $args->setNewValue('updated', new \DateTime());
        }
    }
}
