<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private Task $task;

    public function setUp(): void
    {
        $this->task = new Task();
    }

    public function testId(): void
    {
        $this->assertEquals(null, $this->task->getId());
    }

    public function testCreatedAt(): void
    {
        $date = new \DateTime();

        $this->task->setCreatedAt($date);
        $this->assertEquals($date, $this->task->getCreatedAt());
    }

    public function testTitle(): void
    {
        $this->task->setTitle('Test Title');
        $this->assertEquals('Test Title', $this->task->getTitle());
    }

    public function testContent(): void
    {
        $this->task->setContent('Test content');
        $this->assertEquals('Test content', $this->task->getContent());
    }

    public function testIsDone(): void
    {
        // is false by default
        $this->assertEquals(false, $this->task->isDone());

        // toggle isDone to true
        $this->task->toggle(true);
        $this->assertEquals(true, $this->task->isDone());
    }

}
