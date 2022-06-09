<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

class CategoryDefinition
{
    public function __construct(
        public string $id,
        public string $label,
        public string $description = '',
        public int $order = 0,
    ) {}
}
