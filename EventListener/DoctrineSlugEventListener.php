<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\SlugInterface;
use EB\StringBundle\String\StringService;

/**
 * Class DoctrineSlugEventListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineSlugEventListener
{
    /**
     * @var StringService
     */
    private $string;

    /**
     * @param StringService $string
     */
    public function __construct(StringService $string)
    {
        $this->string = $string;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SlugInterface) {
            $entity->setSlug($this->string->slug($entity->getStringToSlug()));
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SlugInterface) {
            $entity->setSlug($this->string->slug($entity->getStringToSlug()));
        }
    }
}
