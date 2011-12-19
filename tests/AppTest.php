<?php

use Silex\WebTestCase;

class AppTest extends WebTestCase
{
    public function createApplication()
    {
        return require __DIR__.'/../src/app.php';
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
        $this->markTestIncomplete('Still need to implement submitting the form to create an actual paste.');

        $client = $this->createClient();

        $client->request('GET', '/');
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
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
