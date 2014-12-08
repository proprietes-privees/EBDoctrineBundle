<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\SlugInterface;
use EB\DoctrineBundle\Converter\StringConverter;

/**
 * Class DoctrineSlugEventListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineSlugEventListener
{
    /**
     * @var StringConverter
     */
    private $stringConverter;

    /**
     * @param StringConverter $stringConverter
     */
    public function __construct(StringConverter $stringConverter)
    {
        $this->stringConverter = $stringConverter;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SlugInterface) {
            $entity->setSlug($this->stringConverter->slug($entity->getStringToSlug()));
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SlugInterface) {
            $entity->setSlug($this->stringConverter->slug($entity->getStringToSlug()));
        }
    }
}
