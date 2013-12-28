<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait SlugTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait SlugTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * Set Slug
     *
     * @param string $slug
     *
     * @return SlugTrait
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get Slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
