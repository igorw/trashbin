<?php

use Igorw\Trashbin\Validator;

class ValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidate
     */
    public function testValidate($expected, $input)
    {
        $validator = new Validator();
        $this->assertEquals($expected, $validator->validate($input));
    }

    public function provideValidate()
    {
        return array(
            array(array('you must enter some content'), array('content' => '')),
            array(array('you must enter some content'), array('content' => ' ')),
            array(array('you must enter some content'), array('content' => "\t")),
            array(array('you must enter some content'), array('content' => "\t \n")),
            array(array(), array('content' => 'hello')),
            array(array(), array('content' => 'foobar! ')),
            array(array(), array('content' => 'äöü~')),
        );
    }
}
