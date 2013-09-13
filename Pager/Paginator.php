<?php

namespace EB\DoctrineBundle\Pager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Templating\TemplatingRendererEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class Paginator
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class Paginator
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $pageName;

    /**
     * @var string
     */
    private $limitName;

    /**
     * @var string
     */
    private $orderName;

    /**
     * @param EntityManager   $em        Entity manager
     * @param EngineInterface $engine    Templating engine
     * @param string          $pageName  Page argument name
     * @param string          $limitName Limit argument name
     * @param string          $orderName Order argument name
     */
    public function __construct(EntityManager $em, EngineInterface $engine, $pageName, $limitName, $orderName)
    {
        $this->em = $em;
        $this->engine = $engine;
        $this->pageName = $pageName;
        $this->limitName = $limitName;
        $this->orderName = $orderName;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->request = $event->getRequest();
    }

    /**
     * Create a pager
     *
     * @param string $class    Entity class
     * @param array  $criteria Entity filter criteria
     *
     * @return array
     */
    public function createPager($class, array $criteria = array())
    {
        // Count entities
        $rep = $this->em->getRepository($class);
        $qb = $rep->createQueryBuilder('a');
        $countResult = $qb
            ->select($qb->expr()->countDistinct('a.id'))
            ->getQuery()
            ->getArrayResult();
        $count = (int)$countResult[0][1];

        // Prepare limit and offset
        $page = max(1, (int)$this->getRequestValue($this->pageName, 1));
        $limit = (int)$this->getRequestValue($this->limitName, 10);
        $offset = max(0, $limit * ($page - 1));

        return new Pager(
            $rep->findBy($criteria, (array)$this->getRequestValue($this->orderName, array()), $limit, $offset),
            $this->engine->render('EBDoctrineBundle:Pager:pager.html.twig', array(
                'count' => $count,
                'limit' => $limit,
                'offset' => $offset,
                'page' => $page,
            ))
        );
    }

    /**
     * @param string $name    Argument name
     * @param mixed  $default Default argument value
     *
     * @return mixed
     */
    private function getRequestValue($name, $default)
    {
        if (null === $this->request) {
            return $default;
        }

        return $this->request->get($name, $default);
    }
}
