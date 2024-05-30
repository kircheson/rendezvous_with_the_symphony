<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class UserValidator
{
    private $maxLengths = [
        'name' => 255,
        'password' => 255,
        'email' => 255,
    ];

    private $name;
    private $password;
    private $email;
    private $isValid = true;
    private $errors = [];

    public function __construct(RequestStack $requestStack)
    {
        $this->fetchDataFromRequest($requestStack);
        $this->validateData();
    }

    public function fetchDataFromRequest(RequestStack $requestStack): void
    {
        $request = $requestStack->getCurrentRequest();
        if ($request) {
            $this->name = (string)$request->request->get('name');
            $this->password = (string)$request->request->get('password');
            $this->email = (string)$request->request->get('email');
        }
    }

    private function validateData(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max_length'],
            'password' => ['required', 'string', 'max_length', 'min_length', 'uppercase', 'digit'],
            'email' => ['required', 'string', 'max_length', 'email'],
        ];

        foreach ($rules as $fieldName => $fieldRules) {
            $this->validateField($fieldName, $this->{'get' . ucfirst($fieldName)}(), $fieldRules);
        }
    }

    private function validateField(string $fieldName, $value, array $rules): void
    {
        foreach ($rules as $rule) {
            switch ($rule) {
                case 'required':
                    if (empty($value)) {
                        $this->isValid = false;
                        $this->errors[$fieldName] = sprintf('Поле %s обязательно для заполнения.', $fieldName);
                    }
                    break;
                case 'string':
                    if (!is_string($value)) {
                        $this->isValid = false;
                        $this->errors[$fieldName] = sprintf('Поле %s должно быть строкой.', $fieldName);
                    }
                    break;
                case 'max_length':
                    if (strlen($value) > $this->maxLengths[$fieldName]) {
                        $this->isValid = false;
                        $this->errors[$fieldName] = sprintf('Поле %s не должно превышать %d символов.', $fieldName, $this->maxLengths[$fieldName]);
                    }
                    break;
                case 'min_length':
                    if (strlen($value) < 8) {
                        $this->isValid = false;

                        $this->errors[$fieldName] = 'Пароль должен быть длиной не менее 8 символов.';
                    }
                    break;
                case 'uppercase':
                    if (!preg_match('/[A-Z]/', $value)) {
                        $this->isValid = false;
                        $this->errors[$fieldName] = 'Пароль должен содержать как минимум одну заглавную букву.';
                    }
                    break;
                case 'digit':
                    if (!preg_match('/[0-9]/', $value)) {
                        $this->isValid = false;
                        $this->errors[$fieldName] = 'Пароль должен содержать как минимум одну цифру.';
                    }
                    break;
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->isValid = false;
                        $this->errors[$fieldName] = 'Email должен иметь правильный формат.';
                    }
                    break;
            }
        }
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
