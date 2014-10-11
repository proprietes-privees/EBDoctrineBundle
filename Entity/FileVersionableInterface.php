<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Class FileVersionableInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface FileVersionableInterface
{
    /**
     * @return int
     */
    public function getVersion();

    /**
     * @param int $version
     *
     * @return FileVersionableInterface
     */
    public function setVersion($version);
}
