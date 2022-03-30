<?php

namespace App\Tests\Controller;

use App\Tests\SetupTest;

class SecurityControllerTest extends SetupTest
{
    public function testLoginWithBadCredentials(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user';
        $form['_password'] = 'WrongPass';
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-danger:contains("Invalid credentials.")')->count());
    }

    public function testLoginWithGoodCredentials(): void
    {
        $this->loggedAsUser();

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    public function testLogout(): void
    {
        $this->loggedAsUser();

        $crawler = $this->client->request('GET', '/');

        $crawler = $this->client->clickLink('Se déconnecter');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }
}
