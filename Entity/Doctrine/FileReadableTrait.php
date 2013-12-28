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
     * @ORM\Column(type="string")
     */
    private $uri;

    /**
     * Set Uri
     *
     * @param string $uri
     *
     * @return FileReadableTrait
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get Uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}
