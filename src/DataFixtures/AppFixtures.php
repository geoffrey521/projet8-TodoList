<?php

namespace App\DataFixtures;

use App\Factory\TaskFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TaskFactory::createMany(10);

        UserFactory::createMany(10);

        UserFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
            'username' => 'admin',
            'email' => 'admin@todo-co.fr',
            'password' => 'admin'
        ]);

        UserFactory::createOne([
            'username' => 'anonyme',
            'email' => 'anonyme@todo-co.fr'
        ]);

        TaskFactory::createMany(20, function () {
            return [
                'author' => UserFactory::random()
            ];
        });

        $manager->flush();
    }
}
