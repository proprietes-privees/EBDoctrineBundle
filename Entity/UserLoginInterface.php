<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface UserLoginInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface UserLoginInterface
{
    /**
     * Get previousLoginDate
     *
     * @return null|\DateTime
     */
    public function getPreviousLoginDate();

    /**
     * Set previousLoginDate
     *
     * @param null|\DateTime $previousLoginDate
     *
     * @return UserLoginInterface
     */
    public function setPreviousLoginDate(\DateTime $previousLoginDate = null);

    /**
     * Get currentLoginDate
     *
     * @return null|\DateTime
     */
    public function getCurrentLoginDate();

    /**
     * Set currentLoginDate
     *
     * @param null|\DateTime $currentLoginDate
     *
     * @return UserLoginInterface
     */
    public function setCurrentLoginDate(\DateTime $currentLoginDate = null);
}
