<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait UriTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait UriTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $uri;

    /**
     * Set Uri
     *
     * @param string $uri
     *
     * @return UriTrait
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
