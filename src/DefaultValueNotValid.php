<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

class DefaultValueNotValid extends \InvalidArgumentException
{
    public readonly string $key;
    public readonly mixed $default;

    public static function create(string $key, mixed $default): self
    {
        $new = new self();
        $new->key = $key;
        $new->default = $default;

        $new->message = sprintf('Default value %s for setting %s is not valid.', $default, $key);

        return $new;
    }
}
