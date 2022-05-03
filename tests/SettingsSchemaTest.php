<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\SettingsPrototype\FakeServices\MockSchemaData;
use Crell\SettingsPrototype\SchemaType\IntType;
use Crell\SettingsPrototype\SchemaType\StringType;
use PHPUnit\Framework\TestCase;

class SettingsSchemaTest extends TestCase
{
    /**
     * @test
     */
    public function compile(): void
    {
        $schema = new SettingsSchema();

        $schema->newDefinition('foo.bar.baz', new IntType(), 1);
        $schema->newDefinition('beep.boop', new StringType(), 'not set');

        $filename = tempnam(sys_get_temp_dir(), 'compiled');
        try {
            $out = fopen($filename, 'w');

            $code = '<?php return ' . var_export($schema, true) . ';';

            fwrite($out, $code);
            fclose($out);

            // If there's a parse error PHP will
            // throw a ParseError and PHPUnit will catch it for us.
            /** @var SettingsSchema $generated */
            $generated = include($filename);
        }
        finally {
            // Clean up the file, even if a ParseError is
            // thrown above.
            // The OS may be lazy about cleaning up after us, so
            //  it's polite to do so.
            unlink($filename);
        }

        // Now assert various things on $generated as appropriate.
        $this->assertEquals($schema->getDefinition('foo.bar.baz')->default, $generated->getDefinition('foo.bar.baz')->default);
        $this->assertEquals($schema->getDefinition('beep.boop')->default, $generated->getDefinition('beep.boop')->default);
    }

    /**
     * @test
     */
    public function passes(): void
    {
        $schema = new SettingsSchema();

        $schema->addSchema(new MockSchemaData());

        self::assertEquals(1, $schema->getDefinition('foo.bar.baz')->default);
        self::assertEquals('not set', $schema->getDefinition('beep.boop')->default);
    }
}
