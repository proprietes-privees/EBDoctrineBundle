<?php

namespace EB\DoctrineBundle\EventListener;

use EB\DoctrineBundle\Entity\UriableInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use EB\StringBundle\Twig\Extension\StringExtension;

/**
 * Class UriableListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class UriableListener
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
        if ($entity instanceof UriableInterface) {
            $entity->setUri($this->string->uri($entity->getStringToUri()));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof UriableInterface) {
            $entity->setUri($this->string->uri($entity->getStringToUri()));
            $mdt = $args->getEntityManager()->getClassMetadata(get_class($entity));
            $args->getEntityManager()->getUnitOfWork()->recomputeSingleEntityChangeSet($mdt, $entity);
        }
    }
}
