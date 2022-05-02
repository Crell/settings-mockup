<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Attributes;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Setting
{
    public function __construct(
        public readonly string $name,
    ) {}
}