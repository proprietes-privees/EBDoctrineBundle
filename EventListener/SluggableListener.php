<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use EB\DoctrineBundle\Entity\SluggableInterface;
use EB\StringBundle\Stringer;

/**
 * Class SluggableListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class SluggableListener
{
    /**
     * @var Stringer
     */
    private $string;

    /**
     * @param Stringer $string
     */
    public function __construct(Stringer $string)
    {
        $this->string = $string;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SluggableInterface) {
            $entity->setSlug($this->string->search(implode(' ', $entity->getSluggableData())));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SluggableInterface) {
            $entity->setSlug($this->string->search(implode(' ', $entity->getSluggableData())));
        }
    }
}
