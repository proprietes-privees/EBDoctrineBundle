<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Class FileReadableInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface FileReadableInterface extends FileInterface
{
    /**
     * @return string
     */
    public function getComputedUri();

    /**
     * @param string $uri
     *
     * @return FileReadableInterface
     */
    public function setComputedUri($uri);
}
