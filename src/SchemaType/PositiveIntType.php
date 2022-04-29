<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

class PositiveIntType extends IntType
{
    public function __construct(
        public ?int $maxValue = null,
        public int $step = 1,
    ) {
        parent::__construct(minValue: 0);
    }
}
