<?php

namespace App\Service;

use App\Entity\TaskManagerEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskValidator
{
    private $violations;

    public function __construct(ValidatorInterface $validator, TaskManagerEntity $task)
    {
        $violations = $validator->validate($task, null, ['Default']);
        if (count($violations) > 0) {
            $this->violations = $violations;
        }
    }

    public function getViolations(): ?ConstraintViolationListInterface
    {
        return $this->violations ?? null;
    }

    public function isValid(): bool
    {
        return $this->violations === null;
    }
}