<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Hydratable;
use Crell\SettingsPrototype\Validator\AllowedValues;
use Crell\SettingsPrototype\Validator\RegexValidator;
use Crell\SettingsPrototype\Widgets\TextField;
use Crell\SettingsPrototype\Validator\TypeValidator;
use Crell\SettingsPrototype\Widgets\Widget;

class StringType implements SchemaType
{
    use Hydratable;

    public function __construct(
        public ?int $minLength = null,
        public ?int $maxLength = null,
        public ?string $regex = null,
        public ?array $allowedValues = null,
    ) {
    }

    public function defaultValidators(): array
    {
        $ret = [];

        $ret[] = new TypeValidator('string');

        if (!is_null($this->allowedValues)) {
            $ret[] = new AllowedValues($this->allowedValues);
        }

/*
        if (!is_null($this->minLength)) {
            $ret[] = new MinLengthValidator($this->minLength);
        }
        if (!is_null($this->maxLength)) {
            $ret[] = new MaxLengthValidator($this->maxLength);
        }
*/
        if (!is_null($this->regex)) {
            $ret[] = new RegexValidator($this->regex);
        }

        return $ret;
    }

    public function defaultWidget(): Widget
    {
        return new TextField();
    }
}