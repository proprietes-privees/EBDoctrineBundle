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
    public function getComputedVersion();

    /**
     * @param int $version
     *
     * @return FileInterface
     */
    public function setComputedVersion($version);
}
