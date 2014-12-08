<?php

namespace EB\DoctrineBundle\Generator;

/**
 * Class Letter
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class Letter
{
    const ASCII_MAJ_A = 65;
    const ASCII_MAJ_Z = 90;
    const ASCII_A = 97;
    const ASCII_Z = 122;

    /**
     * Next letter
     *
     * @param string $letter
     *
     * @return null|string
     */
    public static function next($letter)
    {
        $ord = ord($letter) + 1;
        if ($ord > self::ASCII_Z) {
            $ord = self::ASCII_A;
        } elseif ($ord > self::ASCII_MAJ_Z && $ord < self::ASCII_A) {
            $ord = self::ASCII_MAJ_A;
        }

        return chr($ord);
    }

    /**
     * Previous letter
     *
     * @param string $letter
     *
     * @return null|string
     */
    public static function previous($letter)
    {
        $ord = ord($letter) - 1;
        if ($ord < self::ASCII_MAJ_A) {
            $ord = self::ASCII_MAJ_Z;
        } elseif ($ord < self::ASCII_A && $ord > self::ASCII_MAJ_Z) {
            $ord = self::ASCII_Z;
        }

        return chr($ord);
    }
}
