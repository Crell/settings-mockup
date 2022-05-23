<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Hydratable;
use Crell\SettingsPrototype\Validator\AllowedValues;
use Crell\SettingsPrototype\Validator\MaxValueValidator;
use Crell\SettingsPrototype\Validator\MinValueValidator;
use Crell\SettingsPrototype\Validator\TypeValidator;
use Crell\SettingsPrototype\Widgets\NumberField;
use Crell\SettingsPrototype\Widgets\SelectField;
use Crell\SettingsPrototype\Widgets\Widget;

class IntType implements SchemaType
{
    use Hydratable;

    public function __construct(
        public ?int $minimum = null,
        public ?int $maximum = null,
        public int $step = 1,
        public ?array $allowedValues = null,
    ) {
    }

    public function defaultValidators(): array
    {
        $ret = [];

        $ret[] = new TypeValidator('int');

        if (!is_null($this->minimum)) {
            $ret[] = new MinValueValidator($this->minimum);
        }
        if (!is_null($this->maximum)) {
            $ret[] = new MaxValueValidator($this->maximum);
        }
        if (!is_null($this->allowedValues)) {
            $ret[] = new AllowedValues($this->allowedValues);
        }

        return $ret;
    }

    public function defaultWidget(): Widget
    {
        if (!$this->allowedValues) {
            return new NumberField();
        }

        return new SelectField(array_combine($this->allowedValues, $this->allowedValues));
    }
}
