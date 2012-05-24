<?php

use Igorw\Trashbin\Parser;

use Symfony\Component\HttpFoundation\Request;

class ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCreatePasteFromRequest
     */
    public function testCreatePasteFromRequest($expected, $parameters)
    {
        $languages = array('php', 'js');
        $parser = new Parser($languages);

        $request = Request::create('GET', '/', $parameters);
        list($id, $paste) = $parser->createPasteFromRequest($request);
        $this->assertTrue(is_string($id));
        $this->assertEquals($expected, $paste);
    }

    public function provideCreatePasteFromRequest()
    {
        return array(
            array(array('content' => 'foobar'), array('content' => 'foobar')),
            array(array('content' => 'foobar', 'language' => 'php'), array('content' => 'foobar', 'language' => 'php')),
            array(array('content' => 'foobar'), array('content' => 'foobar', 'language' => 'spanish')),
        );
    }

    /**
     * @dataProvider provideNormalizeContent
     */
    public function testNormalizeContentShouldFixCarriageReturns($expected, $content)
    {
        $languages = array('php', 'js');
        $parser = new Parser($languages);

        $normalized = $parser->normalizeContent($content);
        $this->assertEquals($expected, $normalized);
    }

    public function provideNormalizeContent()
    {
        return array(
            array("foo\nbar\nbaz\na\n\nb", "foo\r\nbar\rbaz\na\n\rb"),
            array("foobar", "foobar"),
            array("foobar\n", "foobar\n"),
        );
    }

    public function testGenerateIdShouldReturnEightCharactersAlphaNum()
    {
        $languages = array('php', 'js');
        $parser = new Parser($languages);

        $id = $parser->generateId('foobar');
        $this->assertSame(8, strlen($id));
        $this->assertRegExp('/^[a-f0-9]+$/', $id);
    }
}
