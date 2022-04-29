<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class MinValueValidator implements Validator
{
    public function __construct(
        private readonly int|float $min,
    ) {}

    public function validate(mixed $value): ?ValidationError
    {
        if ($value < $this->min) {
            return new TooSmall($this->min, $value);
        }
        return null;
    }
}