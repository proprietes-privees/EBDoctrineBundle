<?php

namespace EB\DoctrineBundle\Salt;

/**
 * Trait SaltGenerator
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
trait SaltGenerator
{
    /**
     * Generate salt
     *
     * @return string
     */
    public static function generateSalt()
    {
        return hash('sha512', uniqid('encode', true) . time() . mt_rand(1, 999999999));
    }
}
