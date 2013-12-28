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
     * @return FileInterface
     */
    public function setVersion($version);
}
