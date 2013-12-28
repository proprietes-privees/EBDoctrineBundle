<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface UriInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface UriInterface
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
     * @return UriInterface
     */
    public function setUri($uri);
}
