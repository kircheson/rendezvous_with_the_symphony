<?php

namespace App\Controller;

use App\Entity\TaskManagerEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/create_task', name: 'task_create', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $task = new TaskManagerEntity();

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $email = $request->request->get('email');
            $task->setTitle($title)
                ->setDescription($description)
                ->setEmail($email)
                ->setCreatedAt(new \DateTime());

            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['task' => $task]);
    }

    #[Route('/tasks', name: 'task_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $tasks = $em->getRepository(TaskManagerEntity::class)->findAll();
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks/edit/{id}', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(EntityManagerInterface $em, Request $request, ?int $id): Response
    {
        $task = $em->getRepository(TaskManagerEntity::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача по ID не найдена');
        }

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $description = $request->request->get('description');
            $email = $request->request->get('email');
            $task->setTitle($title)
                ->setDescription($description)
                ->setEmail($email);

            $em->flush();

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', ['task' => $task]);
    }

}