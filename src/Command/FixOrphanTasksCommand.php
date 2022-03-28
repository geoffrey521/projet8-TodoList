<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\OrphanTasksAssociator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fix-orphan-tasks',
    description: 'Attach orphan tasks to anonymous User.',
)]
class FixOrphanTasksCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private TaskRepository $taskRepository,
        string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command will take all tasks without Author and link them to an Anonymous user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'Orphan task fixer',
            '============',
            ''
        ]);

        $confirm = $io->confirm('All orphan tasks will be associated to Anonymous user. Do you want to continue?', true);

        if ($confirm === true)  {
            $tasks = $this->taskRepository->findBy(['author' => NULL]);
            $anonymousUser = $this->userRepository->findOneBy(['username' => 'anonyme']);

            foreach ($tasks as $task) {
                $task->setAuthor($anonymousUser);
                $this->entityManager->persist($task);
            }

            $this->entityManager->flush();

            $message = empty($tasks) ? 'All tasked are already up to date.' : 'All orphan tasks has been updated';

            $io->success($message);
        }

        return Command::SUCCESS;
    }

}
