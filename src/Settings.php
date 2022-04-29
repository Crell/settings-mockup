<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

class Settings
{
    // @phpstan-ignore-next-line
    private array $pageSettings = [];

    // @phpstan-ignore-next-line
    public function __construct(
        // @phpstan-ignore-next-line
        private array $mockPageData,
        private int $currentPageID,
    ) {}

    public function get(string $key, ?int $pageId = null): mixed
    {
        $pageId ??= $this->getCurrentPageId();

        return $this->getSettingForPage($key, $pageId)
            ?? $this->getSettingFromGlobal($key)
            ?? $this->getSettingFromDefaults($key)
            ?? throw SettingNotFound::create($key, $pageId);

        //return $this->pageSettings[$pageId][$key] ??= $this->getSettingsForPage()

    }

    private function getSettingFromGlobal(string $key): mixed
    {
        return null;
    }

    private function getSettingFromDefaults(string $key): mixed
    {
        return null;
    }

    private function getSettingForPage(string $key, int $pageId): mixed
    {
        while ($pageId && !isset($this->pageSettings[$pageId])) {
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
}
