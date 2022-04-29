<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class TypeValidator implements Validator
{
    public function __construct(private string $type)
    {
    }

    public function validate(mixed $value): ?ValidationError
    {
        if (\get_debug_type($value) !== $this->type) {
            return new IncorrectType($this->type, \get_debug_type($value));
        }
        return null;
    }
}
