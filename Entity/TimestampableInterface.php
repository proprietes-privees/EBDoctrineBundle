<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface TimestampableInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface TimestampableInterface
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
     * @return TimestampableInterface
     */
    public function setCreated(\DateTime $date);

    /**
     * Set updated
     *
     * @param \DateTime $date
     *
     * @return TimestampableInterface
     */
    public function setUpdated(\DateTime $date);
}
