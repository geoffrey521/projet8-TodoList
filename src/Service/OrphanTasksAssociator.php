<?php

namespace App\Service;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;

/**
 * This service will update associate tasks without user
 */
class OrphanTasksAssociator
{
    public function __construct(private TaskRepository $taskRepository, private UserRepository $userRepository)
    {
    }

    /**
     * Get select user named anonymous and associate all orphan tasks
     */
    public function associateToAnonymous()
    {
        $anonymous = $this->userRepository->findOneBy(['username' => 'anonyme']);

        return $anonymous;
    }
}