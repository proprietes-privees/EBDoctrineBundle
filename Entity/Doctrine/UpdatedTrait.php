<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait UpdatedTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait UpdatedTrait
{
    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * Set Updated
     *
     * @param null|\DateTime $updated
     *
     * @return UpdatedTrait
     */
    public function setUpdated(\DateTime $updated = null)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get Updated
     *
     * @return null|\DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
