<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait FileReadableTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait FileReadableTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $uri;

    /**
     * {@inheritdoc}
     */
    public function getComputedUri()
    {
        return $this->getUri();
    }

    /**
     * Get Uri
     *
     * @return null|string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set Uri
     *
     * @param null|string $uri
     *
     * @return FileReadableTrait
     */
    public function setUri($uri = null)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setComputedUri($uri)
    {
        return $this->setUri($uri);
    }
}
