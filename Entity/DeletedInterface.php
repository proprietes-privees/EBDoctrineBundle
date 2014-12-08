<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface DeletedInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface DeletedInterface
{
    /**
     * Get deleted
     *
     * @return null|\DateTime
     */
    public function getDeleted();

    /**
     * Set deleted
     *
     * @param null|\DateTime $deleted
     *
     * @return $this
     */
    public function setDeleted(\DateTime $deleted = null);
}
