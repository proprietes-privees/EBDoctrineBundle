<?php

namespace EB\DoctrineBundle\Tests\Converter;
use EB\DoctrineBundle\Converter\StringConverter;

/**
 * Class StringConverterTest
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class StringConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StringConverter
     */
    private static $string;

    /**
     * Setup tests
     */
    public static function setUpBeforeClass()
    {
        $acc = StringConverter::getAcc();
        self::$string = new StringConverter($acc, array_keys($acc), array_values($acc));
        parent::setUp();
    }

    /**
     * @param string $expected
     * @param string $string
     *
     * @dataProvider getUris
     */
    public function testUri($expected, $string)
    {
        $this->assertEquals($expected, self::$string->uri($string));
    }

    /**
     * @return array
     */
    public function getUris()
    {
        return [
            ['lorem-ipsum-dolor-sit', 'Lorem Ipsum Dolor Sit.'],
            ['lorem-ipsum-dolor-sit', 'Lorem Ipsum Dolor Sit-'],
            ['lorem-ipsum-dolor-sit', 'Lorem Ipsum Dolor Sit'],
            ['lorem-ipsum-dolor-sit', 'Lorem Ipsum Dolor Sit'],
            ['lorem-ipsum-dolor-sit', 'Lôrém Ïpsum Dõlor Sït'],
            ['lorem-ipsum-dolor-sit', 'Lorem_Ipsum_Dolor_Sit'],
            ['lorem-ipsum-dolor-sit', 'Lorem-Ipsum-Dolor-Sit'],
            ['lorem-ipsum-dolor-sit', 'Lorem.Ipsum.Dolor.Sit'],
            ['lorem-ipsum-dolor-sit', '-Lorem Ipsum Dolor Sit'],
            ['lorem-ipsum-dolor-sit', 'Lorem Ipsum Dolor Sit-'],
            ['lorem', '<p>Lorem</p>'],
            ['aaaaaceeeeiiiinooooosuuuuyyz', 'àáäâãçéèëêïîìíñòóöôõšùúüûýÿž'],
        ];
    }

    /**
     * @param string $expected
     * @param array  $args
     *
     * @dataProvider getCuts
     */
    public function testCut($expected, array $args)
    {
        $this->assertEquals($expected, call_user_func_array([self::$string, 'cut'], $args));
    }

    /**
     * @return array
     */
    public function getCuts()
    {
        return [
            ['LoremIpsumDolor ...', ['LoremIpsumDolorSit', 15]],
            ['Lorem Ipsum ...', ['Lorem Ipsum Dolor Sit', 15]],
            ['Lôrém Ïpsum ...', ['Lôrém Ïpsum Dõlor Sït', 15]],
            ['Lôrém Ïpsum (...)', ['Lôrém Ïpsum Dõlor Sït', 15, ' (...)']],
            ['Lorem', ['<p>Lorem</p>', 15]],
        ];
    }

    /**
     * @param string $expected
     * @param string $string
     *
     * @dataProvider getSearchs
     */
    public function testSearch($expected, $string)
    {
        $this->assertEquals($expected, self::$string->search($string));
    }

    /**
     * @return array
     */
    public function getSearchs()
    {
        return [
            ['lorem ipsum dolor sit', 'Lorem Ipsum Dolor Sit'],
            ['lorem ipsum dolor sit', 'Lôrém Ïpsum Dõlor Sït'],
            ['lorem ipsum dolor sit', 'Lorem-Ipsum-Dolor-Sit'],
            ['lorem ipsum dolor sit', 'Lorem Ipsum Dolor Sit Lorem'],
            ['lorem ipsum dolor', 'Lorem Ipsum Dolor Si'],
            ['lorem', '<p>Lorem</p>'],
            ['aaaaaceeeeiiiinooooosuuuuyyz', 'àáäâãçéèëêïîìíñòóöôõšùúüûýÿž'],
        ];
    }

    /**
     * @param string $expected
     * @param string $string
     *
     * @dataProvider getCamelizes
     */
    public function testCamelize($expected, $string)
    {
        $this->assertEquals($expected, self::$string->camelize($string));
    }

    /**
     * @return array
     */
    public function getCamelizes()
    {
        return [
            ['loremIpsumDolorSit', 'Lorem Ipsum Dolor Sit'],
            ['loremIpsumDolorSit', 'Lorem_Ipsum_Dolor_Sit'],
            ['loremIpsumDolorSit', 'Lôrém Ïpsum Dõlor Sït'],
            ['loremIpsumDolorSit', 'Lôrém_Ïpsum_Dõlor_Sït'],
            ['loremipsumdolorsit', 'loremIpsumDolorSit'],
            ['lorem', '<p>Lorem</p>'],
            ['aaaaaceeeeiiiinooooosuuuuyyz', 'àáäâãçéèëêïîìíñòóöôõšùúüûýÿž'],
        ];
    }

    /**
     * @param string $expected
     * @param string $string
     *
     * @dataProvider getUnderscores
     */
    public function testUnderscore($expected, $string)
    {
        $this->assertEquals($expected, self::$string->underscore($string));
    }

    /**
     * @return array
     */
    public function getUnderscores()
    {
        return [
            ['lorem_ipsum_dolor_sit', 'Lorem Ipsum Dolor Sit'],
            ['lorem_ipsum_dolor_sit', 'Lôrém Ïpsum Dõlor Sït'],
            ['lorem_ipsum_dolor_sit', 'lorem_ipsum_dolor_sit'],
            ['lorem', '<p>Lorem</p>'],
            ['aaaaaceeeeiiiinooooosuuuuyyz', 'àáäâãçéèëêïîìíñòóöôõšùúüûýÿž'],
        ];
    }
}
