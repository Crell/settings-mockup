<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Widgets\TextField;
use Crell\SettingsPrototype\Widgets\Widget;

class ColorType implements SchemaType
{
    public function defaultWidget(): Widget
    {
        return new TextField();
    }

    public function defaultValidators(): array
    {
        return [];
    }

}