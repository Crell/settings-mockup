<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Widgets;

class CsvField implements Widget
{
    public function __construct(public string $separator = ',')
    {
    }
}