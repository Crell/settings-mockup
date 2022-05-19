<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\FakeServices;

use Crell\SettingsPrototype\Attributes\Setting;

final class SettingAwareService
{
    public function __construct(
        #[Setting(name: 'foo.bar.baz')]
        private readonly int $foo
    ) {}

    public function getInjectedFoo(): int
    {
        return $this->foo;
    }
}
