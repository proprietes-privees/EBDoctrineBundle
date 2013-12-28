<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait CreatedTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait CreatedTrait
{
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CreatedTrait
     */
    public function setCreated(\DateTime $created = null)
    {
        $this->created = $created;

        return $this;
    }
}
