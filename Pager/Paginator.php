<?php

namespace EB\DoctrineBundle\Pager;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

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
        $count = (int)$qb
            ->select($qb->expr()->countDistinct('a.id'))
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

        // Prepare limit and offset
        $page = max(1, (int)$this->getRequestValue($this->pageName, 1));
        $limit = (int)$this->getRequestValue($this->limitName, 10);
        $offset = max(0, $limit * ($page - 1));

        // Doctrine paginator
        $qb = $rep->createQueryBuilder('a');
        foreach ($criteria as $key => $value) {
            $qb
                ->andWhere($qb->expr()->eq('a.' . $key, ':' . $key))
                ->setParameter($key, $value);
        }
        foreach ((array)$this->getRequestValue($this->orderName, array()) as $key => $value) {
            $qb
                ->addOrderBy('a.' . $key, $value);
        }
        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);

        return new Pager(
            new DoctrinePaginator($qb),
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
