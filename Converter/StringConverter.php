<?php

namespace EB\DoctrineBundle\Converter;

/**
 * Class StringConverter
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class StringConverter
{
    const ENC = 'utf-8';

    /**
     * @var array
     */
    private $acc;

    /**
     * @var array
     */
    private $accKeys;

    /**
     * @var array
     */
    private $accValues;

    /**
     * Load accent values
     *
     * @param array $acc       Array of accent => no accent
     * @param array $accKeys   Keys of first array
     * @param array $accValues Values of first array
     */
    public function __construct(array $acc, array $accKeys, array $accValues)
    {
        $this->acc = $acc;
        $this->accKeys = $accKeys;
        $this->accValues = $accValues;
    }

    /**
     * Accents conversions
     *
     * @return array
     */
    public static function getAcc()
    {
        return [
            'à' => 'a',
            'á' => 'a',
            'ä' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ç' => 'c',
            'é' => 'e',
            'è' => 'e',
            'ë' => 'e',
            'ê' => 'e',
            'ï' => 'i',
            'î' => 'i',
            'ì' => 'i',
            'í' => 'i',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ö' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'š' => 's',
            'ù' => 'u',
            'ú' => 'u',
            'ü' => 'u',
            'û' => 'u',
            'ý' => 'y',
            'ÿ' => 'y',
            'ž' => 'z',
        ];
    }

    /**
     * Cut string
     *
     * @param string $string  String to cut
     * @param int    $length  Desired length for the string
     * @param string $endLine [optional] End of the line
     *
     * @return string
     */
    public function cut($string, $length, $endLine = ' ...')
    {
        // Secure & cut
        $string = strip_tags($string);
        $newString = mb_substr($string, 0, $length, self::ENC);

        // Is this string longer ?
        if ($length >= mb_strlen($string, self::ENC)) {
            return $newString;
        }

        // Cut near last space
        if (false !== $lastSpacePos = mb_strrpos($newString, ' ', self::ENC)) {
            $newString = trim(mb_substr($newString, 0, $lastSpacePos + 1, self::ENC));
        }

        // No ending if sentence finises with a dot
        if ('.' !== mb_substr($newString, mb_strlen($newString, self::ENC) - 1, 1, self::ENC)) {
            $newString .= $endLine;
        }

        return $newString;
    }

    /**
     * Slug
     *
     * @param string $string
     *
     * @return string
     */
    public function slug($string)
    {
        return $this->uri($string);
    }

    /**
     * URI
     *
     * @param string $string
     *
     * @return string
     */
    public function uri($string)
    {
        $string = strip_tags($string);
        $string = mb_strtolower($string, self::ENC);
        $string = str_replace($this->accKeys, $this->accValues, $string);
        $string = trim($string);
        $string = preg_replace('/[^a-z0-9-]+/', '-', $string);
        $string = preg_replace('`(-){2,}`', '-', $string);

        return trim($string, '-');
    }

    /**
     * Search
     *
     * @param string $string
     *
     * @return string
     */
    public function search($string)
    {
        $string = strip_tags($string);
        $string = mb_strtolower($string, self::ENC);
        $string = str_replace($this->accKeys, $this->accValues, $string);
        $string = str_replace('-', ' ', $string);
        $string = trim($string);
        $string = preg_replace('/[^a-z0-9\.-]+/', ' ', $string);
        $string = preg_replace('`( ){2,}`', ' ', $string);
        $string = trim($string);

        $words = array_unique(explode(' ', $string));
        $words = array_filter(array_map(function ($word) {
            return mb_strlen($word, self::ENC) > 2 ? $word : null;
        }, $words));

        return implode(' ', $words);
    }

    /**
     * Camelize
     *
     * @param string $string
     *
     * @return string
     */
    public function camelize($string)
    {
        $string = strip_tags($string);
        $string = mb_strtolower($string, self::ENC);
        $string = str_replace($this->accKeys, $this->accValues, $string);
        $string = mb_strtolower($string, self::ENC);
        $string = preg_replace('/[^a-z0-9\.-]+/', ' ', $string);
        $string = mb_convert_case($string, MB_CASE_TITLE, self::ENC);
        $string = str_replace(' ', '', $string);

        return trim(mb_strtolower(mb_substr($string, 0, 1, self::ENC), self::ENC) . mb_substr($string, 1, null, self::ENC));
    }

    /**
     * Underscore
     *
     * @param string $string
     *
     * @return string
     */
    public function underscore($string)
    {
        $string = strip_tags($string);
        $string = mb_strtolower($string, self::ENC);
        $string = str_replace($this->accKeys, $this->accValues, $string);
        $string = mb_strtolower($string, self::ENC);
        $string = preg_replace('/[^a-z0-9\.-]+/', ' ', $string);
        $string = str_replace(' ', '_', $string);

        return trim($string);
    }
}
