<?php

namespace EB\DoctrineBundle\Pager;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class Pager
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class Pager implements \Countable, \IteratorAggregate
{
    /**
     * @var Paginator
     */
    private $entities;

    /**
     * @var string
     */
    private $template;

    /**
     * @param Paginator $entities Entity list
     * @param string    $template Template
     */
    public function __construct(Paginator $entities, $template)
    {
        $this->entities = $entities;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->entities->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->entities->count();
    }
}
