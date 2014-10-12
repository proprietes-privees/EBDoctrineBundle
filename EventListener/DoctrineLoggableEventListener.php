<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use EB\DoctrineBundle\Entity\LoggableInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class DoctrineLoggableEventListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineLoggableEventListener
{
    /**
     * @var string
     */
    private $persistedMessage;

    /**
     * @var string
     */
    private $updatedMessage;

    /**
     * @var string
     */
    private $removedMessage;

    /**
     * @var null|TranslatorInterface
     */
    private $translator;

    /**
     * @var null|Session
     */
    private $session;

    /**
     * @param string                   $persistedMessage Persisted message
     * @param string                   $updatedMessage   Updated message
     * @param string                   $removedMessage   Removed message
     * @param null|TranslatorInterface $translator       Translator
     */
    public function __construct($persistedMessage, $updatedMessage, $removedMessage, TranslatorInterface $translator = null)
    {
        $this->persistedMessage = $persistedMessage;
        $this->updatedMessage = $updatedMessage;
        $this->removedMessage = $removedMessage;
        $this->translator = $translator;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $session = $event->getRequest()->getSession();
            if ($session instanceof Session) {
                $this->session = $session;
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof LoggableInterface) {
            if ($this->session) {
                $this->session->getFlashBag()->add('success', $this->createMessage($this->persistedMessage, $entity));
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof LoggableInterface) {
            if ($this->session) {
                $this->session->getFlashBag()->add('warning', $this->createMessage($this->updatedMessage, $entity));
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof LoggableInterface) {
            if ($this->session) {
                $this->session->getFlashBag()->add('danger', $this->createMessage($this->removedMessage, $entity));
            }
        }
    }

    /**
     * Create message
     *
     * @param string $template Template message (with %entity% field)
     * @param mixed  $entity   Entity
     *
     * @return string
     */
    private function createMessage($template, $entity)
    {
        if (null === $this->translator) {
            return str_replace('%entity%', $entity, $template);
        }

        return $this->translator->trans($template, ['%entity%' => $entity]);
    }
}
