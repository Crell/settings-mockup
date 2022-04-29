<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

// The type of DATA stored in a settings key.  This can be simple like "String" or "Int",
// or more complex.  The most complex shown here is a typed sequence, which is an array
// of a given type.  Their main purpose is to provide validators and a default widget
// relevant to that type, so that *most* of the time you don't need to set validators
// yourself.
use Crell\SettingsPrototype\Widgets\Widget;

interface SchemaType
{
    public function defaultWidget(): Widget;

    public function defaultValidators(): array;
}