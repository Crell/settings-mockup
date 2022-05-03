<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\Serde\SerdeCommon;
use Crell\SettingsPrototype\FakeServices\MockSchemaData;
use Crell\SettingsPrototype\SchemaType\IntType;
use Crell\SettingsPrototype\SchemaType\StringType;
use Crell\SettingsPrototype\Validator\EvenOdd;
use Crell\SettingsPrototype\Validator\TypeValidator;
use Crell\SettingsPrototype\Widgets\NumberField;
use Crell\SettingsPrototype\Widgets\TextField;
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
    public function compiler_passes_work(): void
    {
        $schema = new SettingsSchema();

        $schema->addSchema(new MockSchemaData());

        self::assertEquals(1, $schema->getDefinition('foo.bar.baz')->default);
        self::assertEquals('not set', $schema->getDefinition('beep.boop')->default);
    }

    /**
     * @test
     */
    public function reading_yaml_files_raw_works(): void
    {
        $schema = new SettingsSchema();

        $schema->addSchema(new RawYamlFilePass(__DIR__ . '/FakeData/basic_settings.yaml'));

        $foo = $schema->getDefinition('foo.bar.baz');
        self::assertEquals(1, $foo->default);
        self::assertEquals('Foo Bar\'s Baz', $foo->form->label);
        self::assertEquals('Stuff here', $foo->form->description);
        self::assertEquals('', $foo->form->help);
        self::assertEquals('', $foo->form->icon);
        self::assertCount(2, $foo->validators);
        self::assertInstanceOf(TypeValidator::class, $foo->validators[0]);
        self::assertInstanceOf(EvenOdd::class, $foo->validators[1]);
        self::assertInstanceOf(NumberField::class, $foo->widget);

        $beep = $schema->getDefinition('beep.boop');
        self::assertEquals('not set', $beep->default);
        self::assertEquals('Beep beep', $beep->form->label);
        self::assertEquals('Roadrunner?', $beep->form->description);
        self::assertEquals('', $beep->form->help);
        self::assertEquals('', $beep->form->icon);
        self::assertCount(1, $beep->validators);
        self::assertInstanceOf(TypeValidator::class, $beep->validators[0]);
        self::assertInstanceOf(TextField::class, $beep->widget);
    }

    /**
     * @test-disabled
     */
    public function reading_yaml_files_with_serde_works(): void
    {
        $schema = new SettingsSchema();

        $schema->addSchema(new SerdeYamlFilePass(__DIR__ . '/FakeData/basic_settings_serde.yaml', new SerdeCommon()));

        self::assertEquals(1, $schema->getDefinition('foo.bar.baz')->default);
        self::assertCount(1, $schema->getDefinition('foo.bar.baz')->validators);
        self::assertInstanceOf(TypeValidator::class, $schema->getDefinition('foo.bar.baz')->validators[0]);
        self::assertEquals('not set', $schema->getDefinition('beep.boop')->default);
    }
}
