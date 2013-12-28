<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait UserPasswordDateTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait UserPasswordDateTrait
{
    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passwordUpdated;

    /**
     * Set PasswordUpdated
     *
     * @param null|\DateTime $passwordUpdated
     *
     * @return UserPasswordDateTrait
     */
    public function setPasswordUpdated(\DateTime $passwordUpdated = null)
    {
        $this->passwordUpdated = $passwordUpdated;

        return $this;
    }

    /**
     * Get PasswordUpdated
     *
     * @return null|\DateTime
     */
    public function getPasswordUpdated()
    {
        return $this->passwordUpdated;
    }
}
