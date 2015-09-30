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
    private $defaultLimit;

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
     * @param null|int $defaultLimit    Default limit
     * @param null|int $maxLimit        Max limit
     * @param bool     $useOutputWalker Use output walker
     */
    public function __construct($defaultLimit = null, $maxLimit = null, $useOutputWalker = false)
    {
        $this->defaultLimit = $defaultLimit;
        $this->maxLimit = $maxLimit;
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
     * @param null|string  $key     QB Key
     *
     * @return $this
     */
    public function applyValidatedFilter(QueryBuilder $qb, array $filters = [], $key = null)
    {
        if (null === $key) {
            $key = $qb->getRootAliases()[0];
        }

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
     * @param QueryBuilder $qb            Query Builder
     * @param array        $defaultOrders Default orders
     * @param null|string  $key           Key
     *
     * @return Paginator
     */
    public function create(QueryBuilder $qb, $defaultOrders = [], $key = null)
    {
        $paginator = new Paginator($this->decorate($qb, $defaultOrders, $key));
        $paginator->setUseOutputWalkers($this->useOutputWalker);

        return $paginator;
    }

    /**
     * Decorate a query builder
     *
     * @param QueryBuilder $qb            Query Builder
     * @param array        $defaultOrders Default orders
     * @param null|string  $key           Key
     *
     * @return QueryBuilder
     */
    public function decorate(QueryBuilder $qb, $defaultOrders = [], $key = null)
    {
        if (null === $key) {
            $key = $qb->getRootAliases()[0];
        }

        $qb
            ->setFirstResult($this->getOffset())
            ->setMaxResults($this->getLimit());

        if (null !== $this->getOrderBy() && null !== $this->getOrderOrder()) {
            if (false !== mb_strpos($this->getOrderBy(), '.')) {
                $qb->addOrderBy($this->getOrderBy(), $this->getOrderOrder());
            } else {
                $qb->addOrderBy(sprintf('%s.%s', $key, $this->getOrderBy()), $this->getOrderOrder());
            }
        }

        foreach ($defaultOrders as $order => $by) {
            if (false !== mb_strpos($order, '.')) {
                $qb->addOrderBy($order, $by);
            } else {
                $qb->addOrderBy(sprintf('%s.%s', $key, $order), $by);
            }
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
        if (null === $this->limit) {
            return $this->defaultLimit;
        }

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
        $this->orderOrder = in_array($orderOrder, ['ASC', 'DESC']) ? $orderOrder : null;

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
            $backupQuery = [];

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
                $backupQuery['order_by'] = $this->getOrderBy();
                $request->query->remove('order_by');
            }
            if ($request->query->has('order_order')) {
                $this->setOrderOrder(mb_strtoupper($request->query->get('order_order')));
                $backupQuery['order_order'] = $this->getOrderOrder();
                $request->query->remove('order_order');
            }

            $request->attributes->set('_query', $backupQuery);
        }
    }

    /**
     * Get DefaultLimit
     *
     * @return null|int
     */
    public function getDefaultLimit()
    {
        return $this->defaultLimit;
    }

    /**
     * Set DefaultLimit
     *
     * @param null|int $defaultLimit
     *
     * @return PaginatorHelper
     */
    public function setDefaultLimit($defaultLimit)
    {
        $this->defaultLimit = $defaultLimit;

        return $this;
    }

    /**
     * Get MaxLimit
     *
     * @return null|int
     */
    public function getMaxLimit()
    {
        return $this->maxLimit;
    }

    /**
     * Set MaxLimit
     *
     * @param null|int $maxLimit
     *
     * @return PaginatorHelper
     */
    public function setMaxLimit($maxLimit = null)
    {
        $this->maxLimit = $maxLimit;

        return $this;
    }

    /**
     * Get UseOutputWalker
     *
     * @return bool
     */
    public function getUseOutputWalker()
    {
        return $this->useOutputWalker;
    }

    /**
     * Set UseOutputWalker
     *
     * @param bool $useOutputWalker
     *
     * @return PaginatorHelper
     */
    public function setUseOutputWalker($useOutputWalker)
    {
        $this->useOutputWalker = $useOutputWalker;

        return $this;
    }
}
