<?php

namespace App\Tests\Controller;

use App\Tests\SetupTest;

class DefaultControllerTest extends SetupTest
{
    public function testViewHomepageAsLoggedUser(): void
    {
        $this->loggedAsUser();

        $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }

    public function testViewHomepageAsNotLoggedUser(): void
    {
        $this->client->request('GET', '/');

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('button', 'Se connecter');
    }


}
