<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

class FormField
{
    public function __construct(
        public string $label = '',
        public string $description = '',
        public string $help = '',
        public string $icon = '',
    ) {
    }
}