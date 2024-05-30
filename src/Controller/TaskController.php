<?php

namespace App\Controller;

use App\Entity\Task;
use App\Service\TaskValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/create_task', name: 'task_show_create', methods: ['GET'])]
    public function showCreate(): Response
    {
        $task = new Task();
        return $this->render('task/create.html.twig', ['task' => $task]);
    }

    #[Route('/create_task', name: 'task_create', methods: ['POST'])]
    public function createTask(RequestStack $requestStack, TaskValidator $taskValidator, EntityManagerInterface $entityManager): Response
    {
        $taskValidator->fetchDataFromRequest($requestStack);

        if (!$taskValidator->isValid()) {
            $errors = $taskValidator->getErrors();
            return $this->render('task/create.html.twig', [
                'task' => new Task(),
                'errors' => $errors,
            ]);
        }

        $task = new Task();
        $task->setTitle($taskValidator->getTitle());
        $task->setDescription($taskValidator->getDescription());
        $task->setEmail($taskValidator->getEmail());

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks', name: 'task_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $tasks = $entityManager->getRepository(Task::class)->findAll();
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/edit/{id}', name: 'task_show_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showEdit(int $id, EntityManagerInterface $entityManager): Response
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача с ID ' . $id . ' не найдена');
        }

        return $this->render('task/edit.html.twig', ['task' => $task]);
    }

    #[Route('/tasks/edit/{id}', name: 'task_edit', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function edit(int $id, RequestStack $requestStack, TaskValidator $taskValidator, EntityManagerInterface $entityManager): Response
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача с ID ' . $id . ' не найдена');
        }

        $taskValidator->fetchDataFromRequest($requestStack);

        if (!$taskValidator->isValid()) {
            $errors = $taskValidator->getErrors();
            return $this->render('task/edit.html.twig', [
                'task' => $task,
                'errors' => $errors,
            ]);
        }

        $task->setTitle($taskValidator->getTitle());
        $task->setDescription($taskValidator->getDescription());
        $task->setEmail($taskValidator->getEmail());

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/delete/{id}', name: 'task_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача не найдена');
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('task_list');
    }
}