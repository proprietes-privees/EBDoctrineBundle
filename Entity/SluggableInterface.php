<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Class SluggableInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface SluggableInterface
{
    /**
     * Return an array of interesting strings or integers
     *
     * @return string[]
     */
    public function getSluggableData();

    /**
     * @param string $slug
     *
     * @return SluggableInterface
     */
    public function setSlug($slug);
}
