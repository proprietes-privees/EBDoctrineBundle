<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait AdvancedUserTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait AdvancedUserTrait
{
    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $accountExpired = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $accountLocked = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $credentialsExpired = false;

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return false === $this->getAccountExpired();
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return false === $this->getAccountLocked();
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return false === $this->getCredentialsExpired();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getEnabled();
    }

    /**
     * Set enabled
     *
     * @param bool $enabled
     *
     * @return AdvancedUserTrait
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set AccountExpired
     *
     * @param bool $accountExpired
     *
     * @return AdvancedUserTrait
     */
    public function setAccountExpired($accountExpired)
    {
        $this->accountExpired = $accountExpired;

        return $this;
    }

    /**
     * Get AccountExpired
     *
     * @return bool
     */
    public function getAccountExpired()
    {
        return $this->accountExpired;
    }

    /**
     * Set AccountLocked
     *
     * @param bool $accountLocked
     *
     * @return AdvancedUserTrait
     */
    public function setAccountLocked($accountLocked)
    {
        $this->accountLocked = $accountLocked;

        return $this;
    }

    /**
     * Get AccountLocked
     *
     * @return bool
     */
    public function getAccountLocked()
    {
        return $this->accountLocked;
    }

    /**
     * Set CredentialsExpired
     *
     * @param bool $credentialsExpired
     *
     * @return AdvancedUserTrait
     */
    public function setCredentialsExpired($credentialsExpired)
    {
        $this->credentialsExpired = $credentialsExpired;

        return $this;
    }

    /**
     * Get CredentialsExpired
     *
     * @return bool
     */
    public function getCredentialsExpired()
    {
        return $this->credentialsExpired;
    }
}
