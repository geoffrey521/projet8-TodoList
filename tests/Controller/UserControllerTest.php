<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker;

class UserControllerTest extends WebTestCase
{
    public function testCreateUser(): void
    {
        $client = static::createClient();

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

        $crawler = $client->followRedirect();

        $this->assertEquals('200', $client->getResponse()->getStatusCode());


    }

}
