<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Hydratable;
use Crell\SettingsPrototype\Validator\MaxValueValidator;
use Crell\SettingsPrototype\Validator\MinValueValidator;
use Crell\SettingsPrototype\Widgets\NumberField;
use Crell\SettingsPrototype\Widgets\Widget;

class IntType implements SchemaType
{
    use Hydratable;

    public function __construct(
        public ?int $minValue = null,
        public ?int $maxValue = null,
        public int $step = 1,
    ) {
    }

    public function defaultValidators(): array
    {
        $ret = [];

        if (!is_null($this->minValue)) {
            $ret[] = new MinValueValidator($this->minValue);
        }
        if (!is_null($this->maxValue)) {
            $ret[] = new MaxValueValidator($this->maxValue);
        }

        return $ret;
    }

    public function defaultWidget(): Widget
    {
        return new NumberField();
    }
}