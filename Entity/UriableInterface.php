<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface UriableInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface UriableInterface
{
    /**
     * Return a string to convert in URI
     *
     * @return string
     */
    public function getStringToUri();

    /**
     * Set a new URI
     *
     * @param string $uri
     *
     * @return UriableInterface
     */
    public function setUri($uri);
}
