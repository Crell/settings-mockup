<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\SettingsPrototype\FakeServices\MockSchemaData;
use Crell\SettingsPrototype\SchemaType\IntType;
use Crell\SettingsPrototype\SchemaType\StringType;
use Crell\SettingsPrototype\Validator\EvenOdd;
use Crell\SettingsPrototype\Validator\TypeValidator;
use Crell\SettingsPrototype\Widgets\NumberField;
use Crell\SettingsPrototype\Widgets\SelectField;
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
    public function compiler_passes_register_correctly(): void
    {
        $schema = new SettingsSchema();

        $schema->addSchema(new MockSchemaData());

        self::assertEquals(1, $schema->getDefinition('foo.bar.baz')->default);
        self::assertEquals('not set', $schema->getDefinition('beep.boop')->default);
    }

    /**
     * @test
     */
    public function reading_yaml_files_raw_parses_correctly(): void
    {
        $schema = new SettingsSchema();

        $schema->addSchema(new RawYamlFilePass(__DIR__ . '/FakeData/basic_settings.yaml'));

        $def = $schema->getDefinition('foo.bar.baz');
        self::assertEquals(1, $def->default);
        self::assertEquals('Foo Bar\'s Baz', $def->form->label);
        self::assertEquals('Stuff here', $def->form->description);
        self::assertEquals('', $def->form->help);
        self::assertEquals('', $def->form->icon);
        self::assertCount(2, $def->validators);
        self::assertInstanceOf(TypeValidator::class, $def->validators[0]);
        self::assertInstanceOf(EvenOdd::class, $def->validators[1]);
        self::assertInstanceOf(NumberField::class, $def->widget);

        $def = $schema->getDefinition('beep.boop');
        self::assertEquals('not set', $def->default);
        self::assertEquals('Beep beep', $def->form->label);
        self::assertEquals('Roadrunner?', $def->form->description);
        self::assertEquals('', $def->form->help);
        self::assertEquals('', $def->form->icon);
        self::assertCount(1, $def->validators);
        self::assertInstanceOf(TypeValidator::class, $def->validators[0]);
        self::assertInstanceOf(SelectField::class, $def->widget);

        $def = $schema->getDefinition('minimalist.definition');
        self::assertEquals('The least I can do', $def->default);
        self::assertEquals('', $def->form->label);
        self::assertEquals('', $def->form->description);
        self::assertEquals('', $def->form->help);
        self::assertEquals('', $def->form->icon);
        self::assertCount(1, $def->validators);
        self::assertInstanceOf(TypeValidator::class, $def->validators[0]);
        self::assertInstanceOf(TextField::class, $def->widget);
    }

    /**
     * @test
     */
    public function reading_yaml_files_raw_rejects_invalid_default(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $schema = new SettingsSchema();

        $schema->addSchema(new RawYamlFilePass(__DIR__ . '/FakeData/invalid_default.yaml'));

        $def = $schema->getDefinition('bad.value');
    }

    /**
     * @test-disabled
     */
    /*
    public function reading_yaml_files_with_serde_works(): void
    {
        $schema = new SettingsSchema();

        $schema->addSchema(new SerdeYamlFilePass(__DIR__ . '/FakeData/basic_settings_serde.yaml', new SerdeCommon()));

        self::assertEquals(1, $schema->getDefinition('foo.bar.baz')->default);
        self::assertCount(1, $schema->getDefinition('foo.bar.baz')->validators);
        self::assertInstanceOf(TypeValidator::class, $schema->getDefinition('foo.bar.baz')->validators[0]);
        self::assertEquals('not set', $schema->getDefinition('beep.boop')->default);
    }
    */
}
