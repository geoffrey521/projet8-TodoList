<?php

namespace App\Tests;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SetupTest extends WebTestCase
{
    protected KernelBrowser $client;
    protected UserRepository $userRepository;
    protected TaskRepository $taskRepository;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->taskRepository = self::getContainer()->get(TaskRepository::class);
    }

    public function loggedAsUser()
    {
        $this->client->request('POST', '/login', ['_username' => 'user', '_password' => 'user']);
    }

    public function loggedAsAdmin()
    {
        $this->client->request('POST', '/login', ['_username' => 'admin', '_password' => 'admin']);
    }

    public function loggedAsUserWithoutTask()
    {
        $this->client->request('POST', '/login', ['_username' => 'UserWithoutTask', '_password' => 'user']);
    }
}