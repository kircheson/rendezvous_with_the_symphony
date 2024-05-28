<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class TaskValidator
{
    private const MAX_TITLE_LENGTH = 255;
    private const MAX_DESCRIPTION_LENGTH = 255;
    private const MAX_EMAIL_LENGTH = 255;

    private $maxLengths = [
        'title' => self::MAX_TITLE_LENGTH,
        'description' => self::MAX_DESCRIPTION_LENGTH,
        'email' => self::MAX_EMAIL_LENGTH,
    ];

    private $title;
    private $description;
    private $email;
    private $isValid = true;
    private $errors = [];

    public function __construct(RequestStack $requestStack)
    {
        $this->fetchDataFromRequest($requestStack);
        $this->validateData();
    }

    private function fetchDataFromRequest(RequestStack $requestStack): void
    {
        $request = $requestStack->getCurrentRequest();
        if ($request) {
            $this->title = $request->request->get('title');
            $this->description = $request->request->get('description');
            $this->email = $request->request->get('email');
        }
    }

    private function validateData(): void
    {
        $this->validateField('title', $this->title, ['required', 'string', 'max_length']);
        $this->validateField('description', $this->description, ['required', 'string', 'max_length']);
        $this->validateField('email', $this->email, ['required', 'string', 'max_length', 'email']);
    }

    private function validateField(string $fieldName, $value, array $rules): void
    {
        if (in_array('required', $rules) && empty($value)) {
            $this->isValid = false;
            $this->errors[$fieldName] = "Заполните $fieldName";
        }

        if (in_array('string', $rules) && !is_string($value)) {
            $this->isValid = false;
            $this->errors[$fieldName] = "$fieldName должен быть строкой";
        }

        if (in_array('max_length', $rules)) {
            $maxLength = $this->getMaxLengthForField($fieldName);
            if (strlen($value) > $maxLength) {
                $this->isValid = false;
                $this->errors[$fieldName] = "$fieldName не должен превышать $maxLength символов";
            }
        }

        if (in_array('email', $rules) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->isValid = false;
            $this->errors[$fieldName] = "Некорректный $fieldName";
        }
    }

    private function getMaxLengthForField(string $fieldName): int
    {
        return $this->maxLengths[$fieldName] ?? 0;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
