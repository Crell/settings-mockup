<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

/**
 * Make configuration objects re-hydrateable.
 *
 * Use this class in any Config object where all desired properties
 * are defined as constructor arguments.  If there are properties to
 * save and load that are not part of the constructor, use the Hydratable
 * trait instead.
 *
 * This is the typical case.
 */
trait ConstructorHydratable
{
    public static function __set_state(array $data): self
    {
        return new self(...$data);
    }
}
