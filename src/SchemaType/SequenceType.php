<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Hydratable;
use Crell\SettingsPrototype\Widgets\TextField;
use Crell\SettingsPrototype\Widgets\Widget;

class SequenceType implements SchemaType
{
    use Hydratable;

    // @todo Do something with the argument.
    // @phpstan-ignore-next-line
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
