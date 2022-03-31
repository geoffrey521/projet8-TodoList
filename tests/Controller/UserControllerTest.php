<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\SetupTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;

class UserControllerTest extends SetupTest
{

    public function testViewListUserNotAdmin()
    {
        $this->loggedAsUser();

        $this->client->request('GET', '/users');

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testViewUserListAsAdmin()
    {
        $this->loggedAsAdmin();

        $crawler = $this->client->request('GET', '/users');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h1:contains("Liste des utilisateurs")')->count());
    }

    public function testCreateUserAsAdmin(): User
    {
        $this->loggedAsAdmin();

        $crawler = $this->client->request('GET', '/users/create');

        $faker = Faker\Factory::create();
        $fakePassword = $faker->password();
        $fakeUsername = $faker->userName();

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $fakeUsername;
        $form['user[password][first]'] = $fakePassword;
        $form['user[password][second]'] = $fakePassword;
        $form['user[email]'] = $faker->email();
        $form['user[roles]'] = 'ROLE_USER';
        $crawler = $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success:contains("Superbe ! L\'utilisateur a bien été ajouté.")')->count());

        return $this->userRepository->findOneBy(['username' => $fakeUsername]);
    }

    /**
     * @depends testCreateUserAsAdmin
     */
    public function testEditUserAsAdmin(User $user): User
    {
        $this->loggedAsAdmin();

        $crawler = $this->client->request('GET', "/users/{$user->getId()}/edit");

        $faker = Faker\Factory::create();
        $fakePassword = $faker->password();

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = $faker->userName();
        $form['user[password][first]'] = $fakePassword;
        $form['user[password][second]'] = $fakePassword;
        $form['user[email]'] = $faker->email();
        $form['user[roles]'] = 'ROLE_USER';
        $crawler = $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success:contains("Superbe ! L\'utilisateur a bien été modifié")')->count());

        return $user;
    }

}
