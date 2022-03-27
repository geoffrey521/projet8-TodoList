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

    public function testCreateTask()
    {
        // start at
        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'];
        $form['task[content]'];

        $crawler = $this->client->submit($form);

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

    }
}
