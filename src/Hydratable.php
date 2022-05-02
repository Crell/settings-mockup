<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

/**
 * Make configuration objects re-hydrateable.
 *
 * Use this class in any Config object where some properties to save
 * and restore are not listed in the constructor.  If everything is in
 * the constructor, use the ConstructorHydratable trait instead as it is faster.
 */
trait Hydratable
{
    public static function __set_state(array $data): static
    {
        static $reflector;
        $reflector ??= new \ReflectionClass(static::class);
        $new = $reflector->newInstanceWithoutConstructor();
        foreach ($data as $k => $v) {
            $new->$k = $v;
        }
        return $new;
    }
}