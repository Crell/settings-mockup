<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

class TooBig implements ValidationError
{
    public function __construct(
        public readonly int|float $expected,
        public readonly int|float $found,
    ) {
    }
}
