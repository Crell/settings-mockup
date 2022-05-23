<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\SettingsPrototype\SchemaType\IntType;
use Crell\SettingsPrototype\SchemaType\StringType;
use Crell\SettingsPrototype\Validator\IncorrectType;
use Crell\SettingsPrototype\Validator\ValidationErrors;
use PHPUnit\Framework\TestCase;

class GetSetTest extends TestCase
{
    /**
     * @test
     */
    public function read_value_in_current_page(): void
    {
        $mockData = [
            1 => ['settings' => ['foo.bar.baz' => 5], 'parent' => 0],
            2 => ['settings' => [], 'parent' => 1],
            3 => ['settings' => ['foo.bar.baz' => 3], 'parent' => 2],
        ];

        $schema = new SettingsSchema();

        $s = new Settings($schema, 3, $mockData);

        $val = $s->get('foo.bar.baz');

        static::assertSame(3, $val);
    }

    /**
     * @test
     */
    public function read_value_in_parent_page(): void
    {
        $mockData = [
            1 => ['settings' => ['foo.bar.baz' => 5], 'parent' => 0],
            2 => ['settings' => [], 'parent' => 1],
            3 => ['settings' => [], 'parent' => 2],
        ];
        $schema = new SettingsSchema();

        $s = new Settings($schema, 3, $mockData);

        $val = $s->get('foo.bar.baz');

        static::assertSame(5, $val);
    }

    /**
     * @test
     */
    public function missing_value_throws(): void
    {
        $this->expectException(SettingNotFound::class);

        $mockData = [
            1 => ['settings' => ['foo.bar.baz' => 5], 'parent' => 0],
            2 => ['settings' => [], 'parent' => 1],
            3 => ['settings' => [], 'parent' => 2],
        ];

        $schema = new SettingsSchema();


        $s = new Settings($schema, 3, $mockData);

        $val = $s->get('missing.value');
    }

    /**
     * @test
     */
    public function schema_update(): void
    {
        $mockData = [
            1 => ['settings' => ['foo.bar.baz' => 5], 'parent' => 0],
            2 => ['settings' => [], 'parent' => 1],
            3 => ['settings' => [], 'parent' => 2],
        ];

        $schema = new SettingsSchema();

        $schema->newDefinition('foo.bar.baz', new IntType(), 1);
        $schema->newDefinition('beep.boop', new StringType(), 'not set');

        $s = new Settings($schema, 3, $mockData);

        $s->setMultiple(2, ['beep.boop' => 'val', 'foo.bar.baz' => 6]);

        self::assertSame('val', $s->get('beep.boop', 2));
        self::assertSame('val', $s->get('beep.boop', 3));
        self::assertSame('not set', $s->get('beep.boop', 1));

        self::assertSame(6, $s->get('foo.bar.baz', 2));
        self::assertSame(5, $s->get('foo.bar.baz', 1));
        self::assertSame(6, $s->get('foo.bar.baz', 3));
    }

    /**
     * @test
     */
    public function update_fails_schema_checks(): void
    {
        $mockData = [
            1 => ['settings' => ['foo.bar.baz' => 5], 'parent' => 0],
            2 => ['settings' => [], 'parent' => 1],
            3 => ['settings' => [], 'parent' => 2],
        ];

        $schema = new SettingsSchema();

        $schema->newDefinition('foo.bar.baz', new IntType(), 1);
        $schema->newDefinition('beep.boop', new StringType(), 'not set');

        $s = new Settings($schema, 3, $mockData);

        try {
            $s->set(2, 'beep.boop', 3.14);
        } catch (ValidationErrors $e) {
            self::assertCount(1, $e->errors);
            self::assertInstanceOf(IncorrectType::class, $e->errors[0]);
            return;
        }

        self::fail('Exception not thrown');
    }
}
