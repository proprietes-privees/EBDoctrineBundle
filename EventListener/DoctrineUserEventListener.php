<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\UserInterface;
use EB\DoctrineBundle\Entity\UserPasswordDateInterface;
use EB\DoctrineBundle\Salt\SaltGenerator;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class DoctrineUserEventListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineUserEventListener
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof UserInterface) {
            if (null !== $raw = $entity->getRawPassword()) {
                $entity->setSalt($salt = SaltGenerator::generateSalt());
                $entity->setPassword($this->encoderFactory->getEncoder($entity)->encodePassword($raw, $salt));
            }
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof UserInterface) {
            if (null !== $raw = $entity->getRawPassword()) {
                $entity->setSalt($salt = SaltGenerator::generateSalt());
                $entity->setPassword($this->encoderFactory->getEncoder($entity)->encodePassword($raw, $salt));

                // Track last password update
                if ($entity instanceof UserPasswordDateInterface) {
                    $entity->setPasswordUpdated(new \DateTime());
                }
            }
        }
    }
}
