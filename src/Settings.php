<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\SettingsPrototype\Validator\ValidationErrors;

class Settings
{
    // @phpstan-ignore-next-line
    private array $pageSettings = [];

    public function __construct(
        private SettingsSchema $schema,
        private int $currentPageID,
        // @phpstan-ignore-next-line
        private array $mockPageData,
    ) {
    }

    public function get(string $key, ?int $pageId = null): mixed
    {
        $pageId ??= $this->getCurrentPageId();

        return $this->getSettingForPage($key, $pageId)
            ?? $this->getSettingFromGlobal($key)
            ?? $this->getSettingFromDefaults($key)
            ?? throw SettingNotFound::create($key, $pageId);
    }

    private function getSettingFromGlobal(string $key): mixed
    {
        return null;
    }

    private function getSettingFromDefaults(string $key): mixed
    {
        return $this->schema->getDefinition($key)?->default;
    }

    private function getSettingForPage(string $key, int $pageId): mixed
    {
        while ($pageId && !isset($this->pageSettings[$pageId][$key])) {
            $this->pageSettings[$pageId] ??= $this->loadSettingsForPage($pageId);
            if (!isset($this->pageSettings[$pageId][$key])) {
                $pageId = $this->getParentPageId($pageId);
            }
        }
        return $this->pageSettings[$pageId][$key] ?? null;
    }

    // @todo Make this real.
    // @phpstan-ignore-next-line
    private function loadSettingsForPage(int $pageId): array
    {
        // Replace with something that reads from the DB.
        return $this->mockPageData[$pageId]['settings'];
    }

    // @todo Make this real.
    private function getParentPageId(int $pageId): int
    {
        return $this->mockPageData[$pageId]['parent'];
    }

    // @todo Make this real.
    private function getCurrentPageId(): int
    {
        return $this->currentPageID;
    }

    public function set(int $pageId, string $key, int|float|string|array $value): void
    {
        $this->setMultiple($pageId, [$key => $value]);
    }

    public function setMultiple(int $pageId, array $values): void
    {
        foreach ($values as $k => $v) {
            if ($errors = $this->schema->validate($k, $v)) {
                throw ValidationErrors::create($k, $errors);
            }
        }

        // @todo Make this real.
        foreach ($values as $k => $v) {
            $this->mockPageData[$pageId]['settings'][$k] = $v;
        }
    }
}
