<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class RegexValidationFailed implements ValidationError
{
    public function __construct(
        public readonly string $value,
    ) {
    }
}
