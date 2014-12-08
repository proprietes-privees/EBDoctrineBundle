<?php

namespace EB\StringBundle\Twig\Extension;

use EB\DoctrineBundle\Converter\StringConverter;

/**
 * Class StringExtension
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class StringExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var StringConverter
     */
    private $stringConverter;

    /**
     * @param string          $name            Extension name
     * @param StringConverter $stringConverter Service
     */
    public function __construct($name, StringConverter $stringConverter)
    {
        $this->name = $name;
        $this->stringConverter = $stringConverter;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('cut', [$this->stringConverter, 'cut']),
            new \Twig_SimpleFilter('uri', [$this->stringConverter, 'uri']),
            new \Twig_SimpleFilter('search', [$this->stringConverter, 'search']),
            new \Twig_SimpleFilter('camelize', [$this->stringConverter, 'camelize']),
            new \Twig_SimpleFilter('underscore', [$this->stringConverter, 'underscore']),
        ];
    }
}
