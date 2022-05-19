<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class AllowedValues implements Validator
{

    public function __construct(private readonly array $values) {}

    public function validate(mixed $value): ?ValidationError
    {
        if (!in_array(needle: $value, haystack: $this->values, strict: true)) {
            return new class($value) implements ValidationError {
                public function __construct(public readonly int $value) {}
            };
        }

        return null;
    }
}
