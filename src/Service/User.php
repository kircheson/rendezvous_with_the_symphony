<?php

namespace App\Service;

use App\Entity\User;
use App\Model\UserDto;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createUser(UserDto $userDto): User
    {
        $user = new User();
        $user->setName($userDto->name);
        $user->setPassword($userDto->password);
        $user->setEmail($userDto->email);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function getUsers(): array
    {
        return $this->em->getRepository(User::class)->findAll();
    }

    public function getUserById(int $id): ?User
    {
        return $this->em->getRepository(User::class)->find($id);
    }

    public function updateUser(User $user, UserDto $updateUserDto): User
    {
        if ($user->getName() !== $updateUserDto->name) {
            $user->setName($updateUserDto->name);
        }

        if ($user->getPassword() !== $updateUserDto->password) {
            $user->setPassword($updateUserDto->password);
        }

        if ($user->getEmail() !== $updateUserDto->email) {
            $user->setEmail($updateUserDto->email);
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}

