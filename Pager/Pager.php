<?php

namespace EB\DoctrineBundle\Pager;

/**
 * Class Pager
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class Pager
{
    /**
     * @var array|object[]
     */
    private $entities;

    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $page;

    /**
     * @param array|object[] $entities Entity list
     * @param int            $count    Entity count
     * @param int            $limit    Limit
     * @param int            $offset   Offset
     * @param int            $page     Page
     */
    public function __construct(array $entities, $count, $limit = 10, $offset = 0, $page = 1)
    {
        $this->entities = $entities;
        $this->count = $count;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->page = $page;
    }

    public function setEntities($entities)
    {
        $this->entities = $entities;

        return $this;
    }

    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Set count
     *
     * @param int $count
     *
     * @return Pager
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set limit
     *
     * @param int $limit
     *
     * @return Pager
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set offset
     *
     * @param int $offset
     *
     * @return Pager
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get offset
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set page
     *
     * @param int $page
     *
     * @return Pager
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }
}
