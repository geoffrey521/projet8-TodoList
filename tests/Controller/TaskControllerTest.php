<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
//
//    public function testSomething(): void
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/');
//
//        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('h1', 'Hello World');
//    }

    public function testCreateTask()
    {
        // start at
        $crawler = $this->client->request('GET', '/');

        $crawler = $this->client->clickLink('Créer une nouvelle tâche');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'];
        $form['task[content]'];

        $crawler = $this->client->submit($form);

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

    }
}
