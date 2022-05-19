<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\SchemaType;

use Crell\SettingsPrototype\Hydratable;
use Crell\SettingsPrototype\Validator\EmailValidator;
use Crell\SettingsPrototype\Validator\TypeValidator;
use Crell\SettingsPrototype\Widgets\TextField;
use Crell\SettingsPrototype\Widgets\Widget;

class EmailType implements SchemaType
{
    use Hydratable;

    public function defaultValidators(): array
    {
        return [
            new TypeValidator('string'),
            new EmailValidator(),
        ];
    }

    public function defaultWidget(): Widget
    {
        return new TextField();
    }
}
