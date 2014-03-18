<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as Assert;

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
    private $enabled;

    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $accountExpired;

    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $accountLocked;

    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $credentialsExpired;

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return
            null === $this->getAccountExpired() ||
            $this->getAccountExpired()->getTimestamp() > time();
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return
            null === $this->getAccountLocked() ||
            $this->getAccountLocked()->getTimestamp() > time();
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return
            null === $this->getCredentialsExpired() ||
            $this->getCredentialsExpired()->getTimestamp() > time();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getEnabled();
    }

    /**
     * Set Enabled
     *
     * @param bool $enabled Enabled
     *
     * @return AdvancedUserTrait
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get Enabled
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
     * @param null|\DateTime $accountExpired AccountExpired
     *
     * @return AdvancedUserTrait
     */
    public function setAccountExpired(\DateTime $accountExpired = null)
    {
        $this->accountExpired = $accountExpired;

        return $this;
    }

    /**
     * Get AccountExpired
     *
     * @return null|\DateTime
     */
    public function getAccountExpired()
    {
        return $this->accountExpired;
    }

    /**
     * Set AccountLocked
     *
     * @param null|\DateTime $accountLocked AccountLocked
     *
     * @return AdvancedUserTrait
     */
    public function setAccountLocked(\DateTime $accountLocked = null)
    {
        $this->accountLocked = $accountLocked;

        return $this;
    }

    /**
     * Get AccountLocked
     *
     * @return null|\DateTime
     */
    public function getAccountLocked()
    {
        return $this->accountLocked;
    }

    /**
     * Set CredentialsExpired
     *
     * @param null|\DateTime $credentialsExpired CredentialsExpired
     *
     * @return AdvancedUserTrait
     */
    public function setCredentialsExpired(\DateTime $credentialsExpired = null)
    {
        $this->credentialsExpired = $credentialsExpired;

        return $this;
    }

    /**
     * Get CredentialsExpired
     *
     * @return null|\DateTime
     */
    public function getCredentialsExpired()
    {
        return $this->credentialsExpired;
    }
}
