<?php

namespace EB\DoctrineBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

/**
 * Interface UserInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface UserInterface extends BaseUserInterface
{
    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return UserInterface
     */
    public function setSalt($salt);

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt();

    /**
     * The user raw password
     *
     * @return string
     */
    public function getRawPassword();

    /**
     * Set encoded password
     *
     * @param string $password
     *
     * @return UserInterface
     */
    public function setPassword($password);

    /**
     * Get a user friendly name
     *
     * @return string
     */
    public function __toString();
}
