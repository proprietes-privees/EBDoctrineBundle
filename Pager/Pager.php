<?php

namespace EB\DoctrineBundle\Pager;

/**
 * Class Pager
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class Pager implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var array|object[]
     */
    private $entities;

    /**
     * @var string
     */
    private $template;

    /**
     * @param array|object[] $entities Entity list
     * @param string         $template Template
     */
    public function __construct(array $entities, $template)
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
        return new \ArrayIterator($this->entities);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->entities);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->entities[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->entities[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->entities[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->entities);
    }
}
