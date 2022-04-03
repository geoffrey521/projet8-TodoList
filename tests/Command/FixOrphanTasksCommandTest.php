<?php

namespace App\Tests\Command;

use App\Command\FixOrphanTasksCommand;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use function Symfony\Component\String\u;

class FixOrphanTasksCommandTest extends KernelTestCase
{
    private UserRepository $userRepository;
    private TaskRepository $taskRepository;
    private EntityManagerInterface $em;

    public function setUp(): void
    {
        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->taskRepository = self::getContainer()->get(TaskRepository::class);
        $this->em = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testFixOrphanTasksCommand(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $application->add(new FixOrphanTasksCommand($this->em, $this->userRepository, $this->taskRepository));

        $command = $application->find('app:fix-orphan-tasks');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName()
        ]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('All orphan tasks has been updated', $output);
    }
}
