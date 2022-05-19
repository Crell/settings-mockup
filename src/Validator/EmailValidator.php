<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class EmailValidator implements Validator
{
    public function validate(mixed $value): ?ValidationError
    {
        if ($value !== '' && !str_contains($value, '@')) {
            return new class($value) implements ValidationError {
                public function __construct(public readonly string $value) {}
            };
        }

        return null;
    }
}
