<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Class TimestampableInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface TimestampableInterface
{
    /**
     * Set creation date
     *
     * @param \DateTime $date
     *
     * @return TimestampableInterface
     */
    public function setCreated(\DateTime $date);

    /**
     * Set updated date
     *
     * @param \DateTime $date
     *
     * @return TimestampableInterface
     */
    public function setUpdated(\DateTime $date);
}
