<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class MaxValueValidator implements Validator
{
    public function __construct(
        private readonly int|float $max,
    ) {}

    public function validate(mixed $value): ?ValidationError
    {
        if ($value > $this->max) {
            return new TooBig($this->max, $value);
        }
        return null;
    }
}
