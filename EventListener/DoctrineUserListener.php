<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\UserInterface;
use EB\DoctrineBundle\Entity\UserPasswordDateInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class DoctrineUserListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineUserListener
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
                $entity->setSalt($salt = $this->generateSalt());
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
                $args->setNewValue('salt', $salt = $this->generateSalt());
                $args->setNewValue('password', $this->encoderFactory->getEncoder($entity)->encodePassword($raw, $salt));

                // Track last password update
                if ($entity instanceof UserPasswordDateInterface) {
                    $args->setNewValue('passwordUpdated', new \DateTime());
                }
            }
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
