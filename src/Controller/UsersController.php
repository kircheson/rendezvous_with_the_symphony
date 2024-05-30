<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

class UsersController extends AbstractController
{
    #[Route('/create_user', name: 'user_show_create', methods: ['GET'])]
    public function showCreate(): Response
    {
        $user = new User();
        return $this->render('users/create.html.twig', ['user' => $user]);
    }

    #[Route('/create_user', name: 'user_create', methods: ['POST'])]
    public function createUser(RequestStack $requestStack, UserValidator $userValidator, EntityManagerInterface $entityManager): Response
    {
        $userValidator->fetchDataFromRequest($requestStack);

        if (!$userValidator->isValid()) {
            $errors = $userValidator->getErrors();
            return $this->render('users/create.html.twig', [
                'user' => new User(),
                'errors' => $errors,
            ]);
        }

        $user = new User();
        $user->setName($userValidator->getName());
        $user->setPassword($userValidator->getPassword());
        $user->setEmail($userValidator->getEmail());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_list');
    }

    #[Route('/users', name: 'user_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('users/list.html.twig', ['users' => $users]);
    }

    #[Route('/users/edit/{id}', name: 'user_show_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showEdit(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь с ID ' . $id . ' не найден');
        }

        return $this->render('users/edit.html.twig', ['user' => $user]);
    }

    #[Route('/users/edit/{id}', name: 'user_edit', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function edit(int $id, RequestStack $requestStack, UserValidator $userValidator, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь с ID ' . $id . ' не найден');
        }

        $userValidator->fetchDataFromRequest($requestStack);

        if (!$userValidator->isValid()) {
            $errors = $userValidator->getErrors();
            return $this->render('users/edit.html.twig', [
                'user' => $user,
                'errors' => $errors,
            ]);
        }

        $user->setName($userValidator->getName());
        $user->setPassword($userValidator->getPassword());
        $user->setEmail($userValidator->getEmail());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_list');
    }

    #[Route('/users/delete/{id}', name: 'user_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_list');
    }
}