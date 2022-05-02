<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Hydratable;
use Crell\SettingsPrototype\Validator\RegexValidator;
use Crell\SettingsPrototype\Widgets\TextField;
use Crell\SettingsPrototype\Widgets\Widget;

class ColorType implements SchemaType
{
    use Hydratable;

    public function defaultWidget(): Widget
    {
        return new TextField();
    }

    public function defaultValidators(): array
    {
        return [new RegexValidator('/#[0-9a-fA-F]{6}/')];
    }

}