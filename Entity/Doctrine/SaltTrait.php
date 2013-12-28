<?php

namespace EB\DoctrineBundle\Entity\Doctrine;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait SaltTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait SaltTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $salt;

    /**
     * Set Salt
     *
     * @param string $salt
     *
     * @return SaltTrait
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get Salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
}
