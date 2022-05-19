<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Hydratable;
use Crell\SettingsPrototype\Validator\TypeValidator;
use Crell\SettingsPrototype\Widgets\CheckboxField;
use Crell\SettingsPrototype\Widgets\Widget;

class BoolType implements SchemaType
{
    use Hydratable;


    public function __construct()
    {
    }

    public function defaultValidators(): array
    {
        return [new TypeValidator('bool')];
    }

    public function defaultWidget(): Widget
    {
        return new CheckboxField();
    }
}