<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Hydratable;
use Crell\SettingsPrototype\Widgets\CheckboxesField;
use Crell\SettingsPrototype\Widgets\Widget;

class MultiListType implements SchemaType
{
    use Hydratable;

    /**
     * @param array<string|int, string> $valueList
     *   Associative array of legal values to a label for those values.
     */
    public function __construct(public array $valueList)
    {
    }

    public function defaultWidget(): Widget
    {
        return new CheckboxesField();
    }

    public function defaultValidators(): array
    {
        return [];
    }
}
