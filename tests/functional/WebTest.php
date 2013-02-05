<?php

use Igorw\Trashbin\ArrayStorage;
use Silex\WebTestCase;

class WebTest extends WebTestCase
{
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

    /** @test */
    public function createPasteWithoutContentShouldFail()
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
        $client = $this->createClient();

        $client->request('GET', '/abcdef12');
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertContains('foobar', $response->getContent());
    }

    public function testCreatePasteWithParent()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/abcdef12');

        $link = $crawler->selectLink('copy')->link();
        $crawler = $client->click($link);

        $form = $crawler->filter('form')->form();
        $this->assertSame('foobar', $form['content']->getValue());
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\HttpException
     * @expectedExceptionMessage paste not found
     */
    public function testViewPasteWithInvalidId()
    {
        $client = $this->createClient();

        $client->request('GET', '/00000000');
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

    public function testErrorHandler()
    {
        $this->app['debug'] = false;

        $client = $this->createClient();

        $client->request('GET', '/non-existent');
        $response = $client->getResponse();
        $this->assertSame(404, $response->getStatusCode());
    }
}
