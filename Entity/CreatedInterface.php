<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface CreatedInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface CreatedInterface
{
    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated();

    /**
     * Set created
     *
     * @param \DateTime $date
     *
     * @return CreatedInterface
     */
    public function setCreated(\DateTime $date);
}
