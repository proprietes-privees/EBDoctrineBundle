<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait UserLoginTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait UserLoginTrait
{
    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $previousLoginDate;

    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $currentLoginDate;

    /**
     * Set PreviousLoginDate
     *
     * @param null|\DateTime $previousLoginDate
     *
     * @return UserLoginTrait
     */
    public function setPreviousLoginDate(\DateTime $previousLoginDate = null)
    {
        $this->previousLoginDate = $previousLoginDate;

        return $this;
    }

    /**
     * Get PreviousLoginDate
     *
     * @return null|\DateTime
     */
    public function getPreviousLoginDate()
    {
        return $this->previousLoginDate;
    }

    /**
     * Set CurrentLoginDate
     *
     * @param null|\DateTime $currentLoginDate
     *
     * @return UserLoginTrait
     */
    public function setCurrentLoginDate(\DateTime $currentLoginDate = null)
    {
        $this->currentLoginDate = $currentLoginDate;

        return $this;
    }

    /**
     * Get CurrentLoginDate
     *
     * @return null|\DateTime
     */
    public function getCurrentLoginDate()
    {
        return $this->currentLoginDate;
    }
}
