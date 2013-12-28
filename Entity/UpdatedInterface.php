<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface UpdatedInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface UpdatedInterface
{
    /**
     * Set updated
     *
     * @param \DateTime $date
     *
     * @return UpdatedInterface
     */
    public function setUpdated(\DateTime $date);
}
