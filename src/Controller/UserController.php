<?php

namespace App\Controller;

use App\Entity\User;
use App\Model\UserDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/create_user', name: 'user_create_get', methods: ['GET'])]
    public function createUserGet(): Response
    {
        return $this->render('users/create.html.twig');
    }

    #[Route('/create_user', name: 'user_create_post', methods: ['POST'])]
    public function createUser(#[MapRequestPayload] UserDto $createUserDTO, EntityManagerInterface $em): Response
    {
        $user = new User();
        $user->setName($createUserDTO->name);
        $user->setPassword($createUserDTO->password);
        $user->setEmail($createUserDTO->email);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user_list');
    }

    #[Route('/users', name: 'user_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();
        return $this->render('users/list.html.twig', ['users' => $users]);
    }

    #[Route('/users/edit/{id}', name: 'user_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function edit(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь с ID ' . $id . ' не найден');
        }

        return $this->render('users/edit.html.twig', ['user' => $user]);
    }

    #[Route('/users/update/{id}', name: 'user_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[Route('/users/update/{id}', name: 'user_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function update(int $id, #[MapRequestPayload] UserDto $updateUserDto, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь с ID ' . $id . ' не найден');
        }

        if ($user->getName() !== $updateUserDto->name) {
            $user->setName($updateUserDto->name);
        }

        if ($user->getPassword() !== $updateUserDto->password) {
            $user->setPassword($updateUserDto->password);
        }

        if ($user->getEmail() !== $updateUserDto->email) {
            $user->setEmail($updateUserDto->email);
        }

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user_list');
    }

    #[Route('/users/delete/{id}', name: 'user_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_list');
    }
}