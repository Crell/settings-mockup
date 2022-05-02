<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Hydratable;

class PositiveIntType extends IntType
{
    use Hydratable;

    public function __construct(
        public ?int $maxValue = null,
        public int $step = 1,
    ) {
        parent::__construct(minValue: 0);
    }
}
