<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

class SettingNotFound extends \InvalidArgumentException
{
    public readonly string $key;
    public readonly int $pageId;

    public static function create(string $key, int $pageId): self
    {
        $new = new self();
        $new->key = $key;
        $new->pageId = $pageId;

        $new->message = sprintf('Settings key %s not found in the context of page $d', $key, $pageId);

        return $new;
    }
}
