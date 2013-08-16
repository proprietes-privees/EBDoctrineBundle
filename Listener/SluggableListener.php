<?php

namespace EB\DoctrineBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use EB\DoctrineBundle\Entity\SluggableInterface;
use EB\StringBundle\String;

/**
 * Class SluggableListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class SluggableListener
{
    /**
     * @var String
     */
    private $string;

    /**
     * @param String $string
     */
    public function __construct(String $string)
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
