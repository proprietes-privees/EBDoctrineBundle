<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface SlugInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface SlugInterface
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
     * @return SlugInterface
     */
    public function setSlug($slug);

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug();
}
