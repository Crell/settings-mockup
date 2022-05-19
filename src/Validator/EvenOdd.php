<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class EvenOdd implements Validator
{

    public function __construct(private readonly bool $even) {}

    public function validate(mixed $value): ?ValidationError
    {
        if ($this->even && $value % 2) {
            return new class($value) implements ValidationError {
                public function __construct(public readonly int $value) {}
            };
        }

        if (!$this->even && !($value % 2)) {
            return new class($value) implements ValidationError {
                public function __construct(public readonly int $value) {}
            };
        }

        return null;
    }
}