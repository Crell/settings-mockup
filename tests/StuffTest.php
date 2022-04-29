<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\SettingsPrototype\SchemaType\IntType;
use Crell\SettingsPrototype\SchemaType\StringType;
use PHPUnit\Framework\TestCase;

class StuffTest extends TestCase
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

        static::assertEquals(3, $val);
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

        static::assertEquals(5, $val);
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
    public function schema(): void
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

        self::assertEquals('val', $s->get('beep.boop', 2));
        self::assertEquals('val', $s->get('beep.boop', 3));
        self::assertEquals('not set', $s->get('beep.boop', 1));

        self::assertEquals(6, $s->get('foo.bar.baz', 2));
        self::assertEquals(5, $s->get('foo.bar.baz', 1));
        self::assertEquals(6, $s->get('foo.bar.baz', 3));
    }
}
