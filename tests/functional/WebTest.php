<?php

use Igorw\Trashbin\ArrayStorage;
use Silex\WebTestCase;

class WebTest extends WebTestCase
{
    private $client;

    public function createApplication()
    {
        $app = require __DIR__.'/../../src/app.php';

        $app['app.storage'] = new ArrayStorage(array(
            'abcdef12' => array('content' => 'foobar', 'created_at' => 1337882841),
        ));

        $app['twig.options'] = array();
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }

    public function setUp()
    {
        parent::setUp();

        $this->client = $this->createClient();
    }

    public function testRoot()
    {
        $this->client->request('GET', '/');
        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk(), '/ should return ok response');
        $this->assertContains('trashbin', $response->getContent());
        $this->assertContains('simple pastebin', $response->getContent());
        $this->assertContains('php', $response->getContent());
        $this->assertContains('textarea', $response->getContent());
    }

    public function testCreatePaste()
    {
        $this->client->setServerParameters(array('REQUEST_TIME' => 1337882841));

        $crawler = $this->client->request('GET', '/');

        $form = $crawler->filter('form')->form();
        $form['content'] = 'foobar';

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertContains('foobar', $response->getContent());
    }

    /** @test */
    public function createPasteWithoutContentShouldFail()
    {
        $crawler = $this->client->request('GET', '/');

        $form = $crawler->filter('form')->form();
        $form['content'] = '';

        $this->client->submit($form);

        $response = $this->client->getResponse();
        $this->assertSame(400, $response->getStatusCode());
    }

    public function testViewPaste()
    {
        $this->client->request('GET', '/abcdef12');
        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertContains('foobar', $response->getContent());
    }

    public function testCreatePasteWithParent()
    {
        $crawler = $this->client->request('GET', '/abcdef12');

        $link = $crawler->selectLink('copy')->link();
        $crawler = $this->client->click($link);

        $form = $crawler->filter('form')->form();
        $this->assertSame('foobar', $form['content']->getValue());
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     * @expectedExceptionMessage paste not found
     */
    public function testViewPasteWithInvalidId()
    {
        $this->client->request('GET', '/00000000');
    }

    public function testAbout()
    {
        $this->client->request('GET', '/about');
        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk(), '/about should return ok response');
        $this->assertContains('trashbin', $response->getContent());
        $this->assertContains('github', $response->getContent());
        $this->assertContains('igorw', $response->getContent());
    }

    public function testErrorHandler()
    {
        $this->app['debug'] = false;

        $this->client = $this->createClient();

        $this->client->request('GET', '/non-existent');
        $response = $this->client->getResponse();
        $this->assertSame(404, $response->getStatusCode());
    }
}
