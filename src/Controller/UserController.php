<?php

namespace App\Controller;

use App\Model\UserDto;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/create_user', name: 'user_create_get', methods: ['GET'])]
    public function createUserGet(): Response
    {
        return $this->render('users/_user_create.html.twig');
    }

    #[Route('/create_user', name: 'user_create_post', methods: ['POST'])]
    public function createUser(#[MapRequestPayload] UserDto $createUserDto, UserService $userService): Response
    {
        $user = $userService->createUser($createUserDto);

        return $this->redirectToRoute('user_list');
    }

    #[Route('/users', name: 'user_list')]
    public function list(UserService $userService): Response
    {
        $users = $userService->getUsers();

        return $this->render('users/_user_list.html.twig', ['users' => $users]);
    }

    #[Route('/users/edit/{id}', name: 'user_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function edit(int $id, UserService $userService): Response
    {
        $user = $userService->getUserById($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь с ID ' . $id . ' не найден');
        }

        return $this->render('users/_user_edit.html.twig', ['user' => $user]);
    }

    #[Route('/users/update/{id}', name: 'user_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function update(int $id, #[MapRequestPayload] UserDto $updateUserDto, UserService $userService): Response
    {
        $user = $userService->getUserById($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь с ID ' . $id . ' не найден');
        }
        $userService->updateUser($user, $updateUserDto);

        return $this->redirectToRoute('user_list');
    }

    #[Route('/users/delete/{id}', name: 'user_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, UserService $userService): Response
    {
        $user = $userService->getUserById($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        $userService->deleteUser($user);

        return $this->redirectToRoute('user_list');
    }
}
