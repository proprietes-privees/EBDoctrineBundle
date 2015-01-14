<?php

namespace EB\DoctrineBundle\Paginator;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class PaginatorHelper
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class PaginatorHelper
{
    /**
     * @var null|int
     */
    private $page;

    /**
     * @var null|int
     */
    private $limit;

    /**
     * @var null|int
     */
    private $maxLimit;

    /**
     * @var null|int
     */
    private $offset;

    /**
     * @var null|string
     */
    private $orderBy;

    /**
     * @var string
     */
    private $orderOrder;

    /**
     * @var bool
     */
    private $useOutputWalker;

    /**
     * @param null|int    $limit           Limit
     * @param null|int    $maxLimit        Max limit
     * @param null|int    $offset          Offset
     * @param null|string $orderBy         Order by
     * @param null|string $orderOrder      Order order
     * @param bool        $useOutputWalker Use output walker
     */
    public function __construct($limit = null, $maxLimit = null, $offset = null, $orderBy = null, $orderOrder = null, $useOutputWalker = false)
    {
        $this->limit = $limit;
        $this->maxLimit = $maxLimit;
        $this->offset = $offset;
        $this->orderBy = $orderBy;
        $this->orderOrder = $orderOrder;
        $this->useOutputWalker = $useOutputWalker;
    }

    /**
     * Create empty instance
     *
     * @return PaginatorHelper
     */
    public static function createEmptyInstance()
    {
        $helper = new self();

        return $helper
            ->setLimit(null)
            ->setOffset(null)
            ->setOrderBy(null)
            ->setOrderOrder(null);
    }

    /**
     * Apply eq filter
     *
     * @param QueryBuilder $qb      QB
     * @param string       $name    Filter name
     * @param array        $filters Filters
     * @param string       $key     QB Key
     *
     * @return $this
     */
    public function applyEqFilter(QueryBuilder $qb, $name, array $filters = [], $key = 'a')
    {
        if (null !== $value = $this->getFilterArgument($name, $filters)) {
            $qb
                ->andWhere($qb->expr()->eq(sprintf('%s.%s', $key, $name), sprintf(':%s', $name)))
                ->setParameter($name, $value);
        }

        return $this;
    }

    /**
     * Apply like filter
     *
     * @param QueryBuilder $qb      QB
     * @param string       $name    Filter name
     * @param array        $filters Filters
     * @param string       $key     QB Key
     *
     * @return $this
     */
    public function applyLikeFilter(QueryBuilder $qb, $name, array $filters = [], $key = 'a')
    {
        if (null !== $value = $this->getFilterArgument($name, $filters)) {
            $qb
                ->andWhere($qb->expr()->like(sprintf('%s.%s', $key, $name), sprintf(':%s', $name)))
                ->setParameter($name, sprintf('%%%s%%', $value));
        }

        return $this;
    }

    /**
     * Apply like filter
     *
     * @param QueryBuilder $qb      QB
     * @param array        $filters Filters
     * @param string       $key     QB Key
     *
     * @return $this
     */
    public function applyValidatedFilter(QueryBuilder $qb, array $filters = [], $key = 'a')
    {
        // Not validated
        if (null !== $value = $this->getFilterArgument('not_validated', $filters)) {
            if (true === $value) {
                $qb
                    ->andWhere($qb->expr()->isNull(sprintf('%s.validated', $key)))
                    ->andWhere($qb->expr()->isNull(sprintf('%s.invalidated', $key)));
            }

            if (false === $value) {
                $qb
                    ->andWhere($qb->expr()->orX(
                        $qb->expr()->isNotNull(sprintf('%s.validated', $key)),
                        $qb->expr()->isNotNull(sprintf('%s.invalidated', $key))
                    ));
            }
        }

        // Valid
        if (null !== $value = $this->getFilterArgument('valid', $filters)) {
            if (true === $value) {
                $qb->andWhere($qb->expr()->isNotNull(sprintf('%s.validated', $key)));
            }

            if (false === $value) {
                $qb->andWhere($qb->expr()->isNotNull(sprintf('%s.invalidated', $key)));
            }
        }

        return $this;
    }

    /**
     * Get filter argument
     *
     * @param string $key     Query builder key
     * @param array  $filters Filters
     *
     * @return null|mixed
     */
    public function getFilterArgument($key, array $filters = [])
    {
        if (array_key_exists($key, $filters)) {
            return $filters[$key];
        }

        return null;
    }

    /**
     * Create a paginator
     *
     * @param QueryBuilder $qb
     * @param array        $defaultOrders Default orders
     * @param string       $key           Key
     *
     * @return Paginator
     */
    public function create(QueryBuilder $qb, $defaultOrders = [], $key = 'a')
    {
        $paginator = new Paginator($this->decorate($qb, $defaultOrders, $key));
        $paginator->setUseOutputWalkers($this->useOutputWalker);

        return $paginator;
    }

    /**
     * Decorate a query builder
     *
     * @param QueryBuilder $qb
     * @param array        $defaultOrders Default orders
     * @param string       $key           Key
     *
     * @return QueryBuilder
     */
    public function decorate(QueryBuilder $qb, $defaultOrders = [], $key = 'a')
    {
        $qb
            ->setFirstResult($this->getOffset())
            ->setMaxResults($this->getLimit());

        if (null !== $this->getOrderBy() && null !== $this->getOrderOrder()) {
            $qb->addOrderBy(sprintf('%s.%s', $key, $this->getOrderBy()), $this->getOrderOrder());
        }
        foreach ($defaultOrders as $order => $by) {
            $qb->addOrderBy(sprintf('%s.%s', $key, $order), $by);
        }

        return $qb;
    }

    /**
     * Get Limit
     *
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set Limit
     *
     * @param int|null $limit Limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        if (null !== $this->maxLimit && null !== $limit) {
            $limit = min($limit, $this->maxLimit);
        }

        $this->limit = $limit;

        return $this;
    }

    /**
     * Get Offset
     *
     * @return int|null
     */
    public function getOffset()
    {
        if ((null !== $limit = $this->getLimit()) && null !== $this->page) {
            if ($this->page > 0) {
                return ($this->page - 1) * $limit;
            }
        }

        return $this->offset;
    }

    /**
     * Set Offset
     *
     * @param int|null $offset Offset
     *
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get OrderBy
     *
     * @return null|string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * Set OrderBy
     *
     * @param null|string $orderBy OrderBy
     *
     * @return $this
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Get OrderOrder
     *
     * @return string
     */
    public function getOrderOrder()
    {
        return $this->orderOrder;
    }

    /**
     * Set OrderOrder
     *
     * @param string $orderOrder OrderOrder
     *
     * @return $this
     */
    public function setOrderOrder($orderOrder)
    {
        $orderOrder = mb_strtolower($orderOrder);
        $this->orderOrder = in_array($orderOrder, ['asc', 'desc']) ? $orderOrder : null;

        return $this;
    }

    /**
     * On kernel request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $request = $event->getRequest();

            // Limit
            if ($request->query->has('limit')) {
                $this->setLimit($request->query->getInt('limit'));
                $request->query->remove('limit');
            }

            // Page
            if ($request->attributes->has('page')) {
                $this->page = $request->attributes->getInt('page');
            }

            // Offset
            if ($request->query->has('offset')) {
                $this->setOffset($request->query->getInt('offset'));
                $request->query->remove('offset');
            }

            // Orders
            if ($request->query->has('order_by')) {
                $this->setOrderBy($request->query->get('order_by'));
                $request->query->remove('order_by');
            }
            if ($request->query->has('order_order')) {
                $this->setOrderBy($request->query->get('order_order'));
                $request->query->remove('order_order');
            }
        }
    }
}
