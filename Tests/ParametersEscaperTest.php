<?php
namespace ExerciseGoogleTranslateBundle\Tests;

use Exercise\GoogleTranslateBundle\ParametersEscaper;

class ParametersEscaperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testEscapeUnescapeString($data)
    {
        $escaper = new ParametersEscaper();

        $escapedString = $escaper->escapeParameters($data);
        $unEscapedString = $escaper->unEscapeParameters($escapedString);

        $this->assertEquals($data, $unEscapedString);
    }

    public function dataProvider()
    {
        return array(
            array('Hello %user%!'),
            array('<img src="%imgSrc%" style="%imgStyle%" />'),
            array('<small>%string%</small> <font color="%color%">"%text%"</font> <i>%length%</i>'),
        );
    }
}
