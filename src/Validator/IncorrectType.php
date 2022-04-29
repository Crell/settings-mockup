<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class IncorrectType implements ValidationError
{
    public function __construct(
        public readonly string $expected,
        public readonly string $found,
    ) {
    }
}