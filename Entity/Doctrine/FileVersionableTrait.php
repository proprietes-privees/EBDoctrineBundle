<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait FileReadableTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait FileVersionableTrait
{
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $version;

    /**
     * {@inheritdoc}
     */
    public function getComputedVersion()
    {
        return $this->getVersion();
    }

    /**
     * Get Version
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set Version
     *
     * @param int $version
     *
     * @return FileVersionableTrait
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setComputedVersion($version)
    {
        return $this->setVersion($version);
    }
}
