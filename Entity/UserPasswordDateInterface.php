<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface UserPasswordDateInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface UserPasswordDateInterface
{
    /**
     * Set password updated
     *
     * @param \DateTime $date
     *
     * @return UserPasswordDateInterface
     */
    public function setPasswordUpdated(\DateTime $date = null);
}
