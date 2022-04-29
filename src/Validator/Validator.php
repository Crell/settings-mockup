<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Validator;

/**
 * Validation errors are not exceptions but objects, so multiple validation
 * errors on the same value can be collected.
 */
interface Validator
{
    // return true on success or a ValidationError object if there's a problem.
    public function validate(mixed $value): ?ValidationError;
}