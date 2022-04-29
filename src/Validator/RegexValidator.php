<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class RegexValidator implements Validator
{
    public function __construct(
        private readonly string $regex,
    ) {}

    public function validate(mixed $value): ?ValidationError
    {
        if (!preg_match($this->regex, $value)) {
            return new RegexValidationFailed($value);
        }
        return null;
    }
}