<?php

namespace EB\DoctrineBundle\Salt;

/**
 * Trait SaltTrait
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait SaltTrait
{
    /**
     * Generate salt
     *
     * @return string
     */
    protected function generateSalt()
    {
        return hash('sha512', uniqid('encode', true) . time() . mt_rand(1, 999999999));
    }
}
