<?php

namespace App\Controller;

use App\Entity\Task;
use App\Model\TaskDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/create_task', name: 'task_create_get', methods: ['GET'])]
    public function createTaskGet(): Response
    {
        return $this->render('task/create.html.twig');
    }

    #[Route('/create_task', name: 'task_create_post', methods: ['POST'])]
    public function createTaskPost(#[MapRequestPayload] TaskDto $createTaskDto, EntityManagerInterface $em): Response
    {
        $task = new Task();
        $task->setTitle($createTaskDto->title);
        $task->setDescription($createTaskDto->description);
        $task->setEmail($createTaskDto->email);

        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks', name: 'task_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $tasks = $em->getRepository(Task::class)->findAll();
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/edit/{id}', name: 'task_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function edit(int $id, EntityManagerInterface $em): Response
    {
        $task = $em->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача с ID ' . $id . ' не найдена');
        }

        return $this->render('task/edit.html.twig', ['task' => $task,]);
    }

    #[Route('/tasks/update/{id}', name: 'task_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $task = $em->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача с ID ' . $id . ' не найдена');
        }

        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $email = $request->request->get('email');

        if ($task->getTitle() !== $title) {
            $task->setTitle($title);
        }

        if ($task->getDescription() !== $description) {
            $task->setDescription($description);
        }

        if ($task->getEmail() !== $email) {
            $task->setEmail($email);
        }

        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/delete/{id}', name: 'task_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(Task::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Задача не найдена');
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('task_list');
    }
}