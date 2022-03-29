<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;

class UserControllerTest extends WebTestCase
{
    public function testViewListUserNotAdmin()
    {
        $client = static::createClient();
        $client->request('POST', '/login', ['_username' => 'user', '_password' => 'user']);

        $client->request('GET', '/users');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testViewUserListAsAdmin()
    {
        $client = static::createClient();
        $client->request('POST', '/login', ['_username' => 'admin', '_password' => 'admin']);

        $crawler = $client->request('GET', '/users');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h1:contains("Liste des utilisateurs")')->count());
    }

    public function testCreateUserAsAdmin(): void
    {
        $client = static::createClient();
        $client->request('POST', '/login', ['_username' => 'admin', '_password' => 'admin']);

        $crawler = $client->request('GET', '/users/create');

        $faker = Faker\Factory::create();
        $fakePassword = $faker->password();

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $faker->userName();
        $form['user[password][first]'] = $fakePassword;
        $form['user[password][second]'] = $fakePassword;
        $form['user[email]'] = $faker->email();
        $form['user[roles]'] = 'ROLE_USER';
        $crawler = $client->submit($form);

        $this->assertEquals('302', $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertEquals('200', $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success:contains("Superbe ! L\'utilisateur a bien été ajouté.")')->count());
    }

    public function testEditUserAsAdmin(): void
    {
        $client = static::createClient();
        $client->request('POST', '/login', ['_username' => 'admin', '_password' => 'admin']);

        $crawler = $client->request('GET', '/users/create');

        $faker = Faker\Factory::create();
        $fakePassword = $faker->password();

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $faker->userName();
        $form['user[password][first]'] = $fakePassword;
        $form['user[password][second]'] = $fakePassword;
        $form['user[email]'] = $faker->email();
        $form['user[roles]'] = 'ROLE_USER';
        $crawler = $client->submit($form);

        $this->assertEquals('302', $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertEquals('200', $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success:contains("Superbe ! L\'utilisateur a bien été ajouté.")')->count());
    }

    public function testDeleteUserAsAdmin()
    {
        $client = static::createClient();
        $client->request('POST', '/login', ['_username' => 'admin', '_password' => 'admin']);

        $crawler = $client->request('GET', '/users/delete');
    }


}
