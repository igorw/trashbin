<?php

use Silex\WebTestCase;

class FunctionalTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../src/app.php';

        $app['catch_exceptions'] = false;

        $app['app.storage'] = $this->getMockBuilder('Igorw\Trashbin\Storage')->disableOriginalConstructor()->getMock();

        $app['twig.options'] = array('debug' => true);

        unset($app['exception_handler']);

        return $app;
    }

    public function testRoot()
    {
        $client = $this->createClient();

        $client->request('GET', '/');
        $response = $client->getResponse();
        $this->assertTrue($response->isOk(), '/ should return ok response');
        $this->assertContains('trashbin', $response->getContent());
        $this->assertContains('simple pastebin', $response->getContent());
        $this->assertContains('php', $response->getContent());
        $this->assertContains('textarea', $response->getContent());
    }

    public function testCreatePaste()
    {
        $paste = array('content' => 'foobar', 'created_at' => 1337882841);

        $this->app['app.storage']
            ->expects($this->once())
            ->method('set')
            ->with($this->isType('string'), $paste);

        $this->app['app.storage']
            ->expects($this->once())
            ->method('get')
            ->with($this->isType('string'))
            ->will($this->returnValue($paste));

        $client = $this->createClient();
        $client->setServerParameters(array('REQUEST_TIME' => 1337882841));

        $crawler = $client->request('GET', '/');

        $form = $crawler->filter('form')->form();
        $form['content'] = 'foobar';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertContains('foobar', $response->getContent());
    }

    public function testCreatePasteWithoutContentShouldFail()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/');

        $form = $crawler->filter('form')->form();
        $form['content'] = '';

        $client->submit($form);

        $response = $client->getResponse();
        $this->assertSame(400, $response->getStatusCode());
    }

    public function testViewPaste()
    {
        $paste = array('content' => 'foobar', 'created_at' => 1337882841);

        $this->app['app.storage']
            ->expects($this->once())
            ->method('get')
            ->with('abcdef12')
            ->will($this->returnValue($paste));

        $client = $this->createClient();

        $client->request('GET', '/abcdef12');
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertContains('foobar', $response->getContent());
    }

    public function testViewPasteWithInvalidId()
    {
        $this->app['catch_exceptions'] = true;

        $this->app['app.storage']
            ->expects($this->once())
            ->method('get')
            ->with('00000000')
            ->will($this->returnValue(null));

        $client = $this->createClient();

        $client->request('GET', '/00000000');
        $response = $client->getResponse();
        $this->assertSame(404, $response->getStatusCode());
        $this->assertContains('paste not found', $response->getContent());
    }

    public function testAbout()
    {
        $client = $this->createClient();

        $client->request('GET', '/about');
        $response = $client->getResponse();
        $this->assertTrue($response->isOk(), '/about should return ok response');
        $this->assertContains('trashbin', $response->getContent());
        $this->assertContains('github', $response->getContent());
        $this->assertContains('igorw', $response->getContent());
    }
}
