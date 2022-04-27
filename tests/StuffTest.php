<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

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

        $s = new Settings($mockData, 3);

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

        $s = new Settings($mockData, 3);

        $val = $s->get('foo.bar.baz');

        static::assertEquals(5, $val);
    }
}
