<?php

namespace EB\DoctrineBundle\Converter;

use EB\DoctrineBundle\Entity\LoggableInterface;

/**
 * Class StringConverter
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class StringConverter
{
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
     * @var string
     */
    private $encoding;

    /**
     * Load accent values
     *
     * @param array  $acc       Array of accent => no accent
     * @param array  $accKeys   Keys of first array
     * @param array  $accValues Values of first array
     * @param string $encoding  Encoding
     */
    public function __construct(array $acc, array $accKeys, array $accValues, $encoding = 'utf-8')
    {
        $this->acc = $acc;
        $this->accKeys = $accKeys;
        $this->accValues = $accValues;
        $this->encoding = $encoding;
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
     * Camelize
     *
     * @param string $string
     *
     * @return string
     */
    public function camelize($string)
    {
        $string = strip_tags($string);
        $string = mb_strtolower($string, $this->encoding);
        $string = str_replace($this->accKeys, $this->accValues, $string);
        $string = mb_strtolower($string, $this->encoding);
        $string = preg_replace('/[^a-z0-9\.-]+/', ' ', $string);
        $string = mb_convert_case($string, MB_CASE_TITLE, $this->encoding);
        $string = str_replace(' ', '', $string);

        return trim(mb_strtolower(mb_substr($string, 0, 1, $this->encoding), $this->encoding) . mb_substr($string, 1, null, $this->encoding));
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
        $newString = mb_substr($string, 0, $length, $this->encoding);

        // Is this string longer ?
        if ($length >= mb_strlen($string, $this->encoding)) {
            return $newString;
        }

        // Cut near last space
        if (false !== $lastSpacePos = mb_strrpos($newString, ' ', $this->encoding)) {
            $newString = trim(mb_substr($newString, 0, $lastSpacePos + 1, $this->encoding));
        }

        // No ending if sentence finises with a dot
        if ('.' !== mb_substr($newString, mb_strlen($newString, $this->encoding) - 1, 1, $this->encoding)) {
            $newString .= $endLine;
        }

        return $newString;
    }

    /**
     * Flatten
     *
     * @param mixed $value
     *
     * @return string
     */
    public function flatten($value)
    {
        if (is_null($value)) {
            return 'null';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_scalar($value)) {
            return $value;
        }
        if (is_array($value)) {
            return json_encode($value);
        }
        if ($value instanceof \DateTime) {
            return $value->format('d/m/Y H:i:s');
        }
        if ($value instanceof LoggableInterface) {
            return (string)$value;
        }
        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return (string)$value;
            }
            if (method_exists($value, 'getId')) {
                return sprintf('%s[%u]', get_class($value), call_user_func([$value, 'getId']));
            }
            if ($value instanceof \Serializable) {
                return serialize($value);
            }

            return get_class($value);
        }

        return '?';
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
        $string = mb_strtolower($string, $this->encoding);
        $string = str_replace($this->accKeys, $this->accValues, $string);
        $string = str_replace('-', ' ', $string);
        $string = trim($string);
        $string = preg_replace('/[^a-z0-9\.-]+/', ' ', $string);
        $string = preg_replace('`( ){2,}`', ' ', $string);
        $string = trim($string);

        $words = array_unique(explode(' ', $string));
        $words = array_filter(array_map(function ($word) {
            return mb_strlen($word, $this->encoding) > 2 ? $word : null;
        }, $words));

        return implode(' ', $words);
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
     * Underscore
     *
     * @param string $string
     *
     * @return string
     */
    public function underscore($string)
    {
        $string = strip_tags($string);
        $string = mb_strtolower($string, $this->encoding);
        $string = str_replace($this->accKeys, $this->accValues, $string);
        $string = mb_strtolower($string, $this->encoding);
        $string = preg_replace('/[^a-z0-9\.-]+/', ' ', $string);
        $string = str_replace(' ', '_', $string);

        return trim($string);
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
        $string = mb_strtolower($string, $this->encoding);
        $string = str_replace($this->accKeys, $this->accValues, $string);
        $string = trim($string);
        $string = preg_replace('/[^a-z0-9-]+/', '-', $string);
        $string = preg_replace('`(-){2,}`', '-', $string);

        return trim($string, '-');
    }
}
