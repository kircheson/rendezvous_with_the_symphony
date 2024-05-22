<?php

namespace App\Controller;

use App\Entity\TaskManagerEntity;
use App\Service\TaskValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/create_task', name: 'task_show_create', methods: ['GET'])]
    public function showCreate(EntityManagerInterface $em, Request $request): Response
    {
        $task = new TaskManagerEntity();
        return $this->render('task/create.html.twig', ['task' => $task]);
    }

    #[Route('/create_task', name: 'task_create', methods: ['POST'])]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $task = new TaskManagerEntity();

        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $email = $request->request->get('email');
        $task->setTitle($title)
            ->setDescription($description)
            ->setEmail($email)
            ->setCreatedAt(new \DateTime());

        $validator = new TaskValidator($this->validator, $task);
        if (!$validator->isValid()) {
            $violations = $validator->getViolations();
            return $this->render('task/create.html.twig', ['task' => $task, 'violations' => $violations]);
        }

        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks', name: 'task_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $tasks = $em->getRepository(TaskManagerEntity::class)->findAll();
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/edit/{id}', name: 'task_show_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showEdit(EntityManagerInterface $em, Request $request, ?int $id): Response
    {
        $task = $em->getRepository(TaskManagerEntity::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача с ID ' . $id . ' не найдена');
        }

        return $this->render('task/edit.html.twig', ['task' => $task]);
    }

    #[Route('/tasks/edit/{id}', name: 'task_edit', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function edit(EntityManagerInterface $em, Request $request, ?int $id): Response
    {
        $task = $em->getRepository(TaskManagerEntity::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача с ID ' . $id . ' не найдена');
        }

        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $email = $request->request->get('email');
        $task->setTitle($title)
            ->setDescription($description)
            ->setEmail($email);

        $validator = new TaskValidator($this->validator, $task);
        if (!$validator->isValid()) {
            $violations = $validator->getViolations();
            return $this->render('task/edit.html.twig', ['task' => $task, 'violations' => $violations]);
        }

        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/delete/{id}', name: 'task_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(EntityManagerInterface $em, Request $request, int $id): Response
    {

        $task = $em->getRepository(TaskManagerEntity::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача не найдена');
        }

        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('task_list');
    }
}