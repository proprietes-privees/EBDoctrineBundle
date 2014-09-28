<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\DateTime()
     */
    private $previousLoginDate;

    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $currentLoginDate;

    /**
     * Set PreviousLoginDate
     *
     * @param null|\DateTime $previousLoginDate
     *
     * @return $this
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
     * @return $this
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
