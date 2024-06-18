<?php

namespace App\Controller;

use App\Model\UserDto;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/create_user', name: 'user_create_get', methods: ['GET'])]
    public function createUserGet(): Response
    {
        return $this->render('users/_user_create.html.twig');
    }

    #[Route('/create_user', name: 'user_create_post', methods: ['POST'])]
    public function createUser(#[MapRequestPayload] UserDto $createUserDto, UserService $userService): Response
    {
        $user = $userService->create($createUserDto);

        $this->em->flush();

        return $this->redirectToRoute('user_list');
    }

    #[Route('/users', name: 'user_list')]
    public function list(UserService $userService): Response
    {
        $users = $userService->getAll();

        return $this->render('users/_user_list.html.twig', ['users' => $users]);
    }

    #[Route('/users/edit/{id}', name: 'user_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function edit(int $id, UserService $userService): Response
    {
        $user = $userService->get($id);

        return $this->render('users/_user_edit.html.twig', ['user' => $user]);
    }

    #[Route('/users/update/{id}', name: 'user_update', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function update(int $id, #[MapRequestPayload] UserDto $updateUserDto, UserService $userService): Response
    {
        $user = $userService->update($id, $updateUserDto);

        $this->em->flush();

        return $this->redirectToRoute('user_list');
    }

    #[Route('/users/delete/{id}', name: 'user_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(int $id, UserService $userService): Response
    {
        $user = $userService->delete($id);

        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('user_list');
    }
}