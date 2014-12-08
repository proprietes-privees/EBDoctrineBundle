<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait DeletedTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait DeletedTrait
{
    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $deleted;

    /**
     * Get deleted
     *
     * @return null|\DateTime
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deleted
     *
     * @param null|\DateTime $deleted
     *
     * @return $this
     */
    public function setDeleted(\DateTime $deleted = null)
    {
        $this->deleted = $deleted;

        return $this;
    }
}
