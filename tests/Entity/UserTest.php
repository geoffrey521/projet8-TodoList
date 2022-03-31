<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Monolog\Test\TestCase;

class UserTest extends TestCase
{

    private User $user;
    private Task $task;

    public function setUp(): void
    {
        $this->user = new User();
        $this->task = new Task();
    }

    public function testId(): void
    {
        $this->assertEquals(null, $this->user->getId());
    }

    public function testUsername(): void
    {
        $this->user->setUsername('testname');
        $this->assertEquals('testname', $this->user->getUsername());
    }

    public function testPassword(): void
    {
        $this->user->setPassword('password');
        $this->assertEquals('password', $this->user->getPassword());
    }

    public function testEmail(): void
    {
        $this->user->setEmail('test@test.test');
        $this->assertEquals('test@test.test', $this->user->getEmail());
    }

    public function testGetSalt(): void
    {
        self::assertEquals(null, $this->user->getSalt());
    }

    public function testRoles(): void
    {
        $this->user->setRoles(['ROLE_USER']);
        self::assertEquals(['0' => 'ROLE_USER'], $this->user->getRoles());
    }

    // testing add, get and remove task functions
    public function testTask(): void
    {
        $response = $this->user->addTask($this->task);

        $this->assertInstanceOf(User::class, $response);
        $this->assertSame(1, $this->user->getTasks()->count());

        $this->assertTrue($this->user->getTasks()->contains($this->task));

        $this->user->removeTask($this->task);
        $this->assertSame(0, $this->user->getTasks()->count());
        $this->assertFalse($this->user->getTasks()->contains($this->task));
    }
}
