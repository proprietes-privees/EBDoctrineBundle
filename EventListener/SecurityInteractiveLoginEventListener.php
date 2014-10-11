<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\EntityManager;
use EB\DoctrineBundle\Entity\UserLoginInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class SecurityInteractiveLoginEventListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class SecurityInteractiveLoginEventListener
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em Entity manager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if ($user instanceof UserLoginInterface) {
            $user->setPreviousLoginDate($user->getCurrentLoginDate());
            $user->setCurrentLoginDate(new \DateTime());
            $this->em->flush();
        }
    }
}
