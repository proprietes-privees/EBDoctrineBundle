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
     * Return a string to uri
     *
     * @return string
     */
    public function getStringToUri();

    /**
     * Set uri
     *
     * @param string $uri
     *
     * @return UriableInterface
     */
    public function setUri($uri);
}
