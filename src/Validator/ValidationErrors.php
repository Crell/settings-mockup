<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class ValidationErrors extends \InvalidArgumentException
{
    public readonly string $key;
    public readonly array $errors;

    /**
     * @param ValidationError[] $errors
     */
    public static function create(string $key, array $errors): self
    {
        $new = new self();
        $new->key = $key;
        $new->errors = $errors;

        $new->message = sprintf('%d validation errors found', count($errors));

        return $new;
    }
}
