<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     */
    private $uri;

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
     * @return $this
     */
    public function setUri($uri = null)
    {
        $this->uri = $uri;

        return $this;
    }
}
