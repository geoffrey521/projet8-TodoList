<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Tests\SetupTest;
use Faker;

class TaskControllerTest extends SetupTest
{
    public function testViewTaskListAsUser(): void
    {
        $this->loggedAsUser();

        $crawler = $this->client->request('GET', '/tasks');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateTaskAsUser(): Task
    {
        $this->loggedAsUser();

        $crawler = $this->client->request('GET', '/tasks/create');

        $faker = Faker\Factory::create();

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = $faker->words(3, true);
        $form['task[content]'] = $faker->text(100);
        $crawler = $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success:contains("Superbe ! La tâche a été bien été ajoutée.")')->count());

        return $this->taskRepository->findBy([],['id' => 'desc'],1)[0];
    }

    /**
     * @depends testCreateTaskAsUser
     */
    public function testEditTaskAsAuthor(Task $task): Task
    {
        $this->loggedAsUser();

        $crawler = $this->client->request('GET', "/tasks/{$task->getId()}/edit");
        $faker = Faker\Factory::create();

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = $faker->words(3, true);
        $form['task[content]'] = $faker->text(100);
        $crawler = $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success:contains("Superbe ! La tâche a bien été modifiée.")')->count());

        return $task;
    }

    /**
     * @depends testCreateTaskAsUser
     */
    public function testEditTaskAsNotAuthor(Task $task): void
    {
        $this->loggedAsUserWithoutTask();

        $crawler = $this->client->request('GET', "/tasks/{$task->getId()}/edit");

        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @depends testEditTaskAsAuthor
     */
    public function testToggleTask(Task $task): void
    {
        $this->loggedAsUser();

        $crawler = $this->client->request('GET', "/tasks/{$task->getId()}/toggle");

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success:contains("a bien été marquée comme faite.")')->count());
    }

    /**
     * @depends testEditTaskAsAuthor
     */
    public function testDeleteTaskAsUser(Task $task)
    {
        $this->loggedAsUser();

        $crawler = $this->client->request('GET', "/tasks/{$task->getId()}/delete");
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteAnonymousTaskAsUser(): void
    {
        $this->loggedAsUser();
        $taskAuthor = $this->userRepository->findOneBy(['username' => 'anonyme']);

        $task = $this->taskRepository->findBy(['author' => $taskAuthor],['id' => 'desc'],1)[0];

        $crawler = $this->client->request('GET', "/tasks/{$task->getId()}/delete");

        $this->assertSame(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteAnonymousTaskAsAdmin(): void
    {
        $this->loggedAsAdmin();
        $taskAuthor = $this->userRepository->findOneBy(['username' => 'anonyme']);

        $task = $this->taskRepository->findBy(['author' => $taskAuthor],['id' => 'desc'],1)[0];

        $crawler = $this->client->request('GET', "/tasks/{$task->getId()}/delete");

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }


}
