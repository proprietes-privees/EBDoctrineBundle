<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use EB\DoctrineBundle\Entity\DeletedInterface;

/**
 * Class DoctrineDeletedEventListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineDeletedEventListener
{
    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof DeletedInterface) {
                // Hard delete will be done if we delete again
                if (null === $entity->getDeleted()) {
                    $entity->setDeleted(new \DateTime());

                    $args->getEntityManager()->persist($entity);
                    $uow->propertyChanged($entity, 'deleted', null, $entity->getDeleted());
                    $uow->scheduleExtraUpdate($entity, ['deleted' => [null, $entity->getDeleted()]]);
                }
            }
        }
    }
}
