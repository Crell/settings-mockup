<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Widgets\TextField;
use Crell\SettingsPrototype\Widgets\Widget;

class SequenceType implements SchemaType
{
    public function __construct(string $valueType = 'string')
    {
    }

    public function defaultWidget(): Widget
    {
        return new TextField();
    }

    public function defaultValidators(): array
    {
        return [];
    }
}
