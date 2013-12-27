<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface SluggableInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface SluggableInterface
{
    /**
     * Return a string to slug
     *
     * @return string
     */
    public function getStringToSlug();

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return SluggableInterface
     */
    public function setSlug($slug);
}
