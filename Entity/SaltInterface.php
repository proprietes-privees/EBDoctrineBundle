<?php

namespace EB\DoctrineBundle\Entity;

/**
 * Interface SaltedInterface
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
interface SaltInterface
{
    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return SaltInterface
     */
    public function setSalt($salt);
}
