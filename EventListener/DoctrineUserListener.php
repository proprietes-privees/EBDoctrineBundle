<?php

namespace EB\UserBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\UserBundle\Entity\UserInterface;
use EB\UserBundle\Entity\UserPasswordDateInterface;
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
            $this->encodePassword($entity);
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof UserInterface) {
            $this->encodePassword($entity);

            $mdt = $args->getEntityManager()->getClassMetadata(get_class($entity));
            $args->getEntityManager()->getUnitOfWork()->recomputeSingleEntityChangeSet($mdt, $entity);
        }
    }

    /**
     * @param UserInterface $user
     */
    private function encodePassword(UserInterface $user)
    {
        if (null !== $rawPassword = $user->getRawPassword()) {
            $user->setSalt(hash('sha512', uniqid('encode', true) . time() . mt_rand(1, 999999999)));
            $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($rawPassword, $user->getSalt()));

            // Track last password update
            if ($user instanceof UserPasswordDateInterface) {
                $user->setPasswordUpdated(new \DateTime());
            }
        }
    }
}
