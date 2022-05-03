<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Widgets;

class SelectWidget implements Widget
{
    /**
     * @param array<string, string> $values
     */
    public function __construct(
        protected array $values,
    ) {}
}