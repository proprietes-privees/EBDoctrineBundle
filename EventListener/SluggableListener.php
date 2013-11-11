<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use EB\DoctrineBundle\Entity\SluggableInterface;
use EB\StringBundle\Twig\Extension\StringExtension;

/**
 * Class SluggableListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class SluggableListener
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
