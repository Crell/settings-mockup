<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\FakeServices;

use Crell\SettingsPrototype\SchemaType\IntType;
use Crell\SettingsPrototype\SchemaType\StringType;
use Crell\SettingsPrototype\SettingsSchema;

class MockSchemaData
{
    public function __invoke(SettingsSchema $schema): void
    {
        $schema->newDefinition('foo.bar.baz', new IntType(), 1);
        $schema->newDefinition('beep.boop', new StringType(), 'not set');
    }
}
