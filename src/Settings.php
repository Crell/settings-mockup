<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\SettingsPrototype\Validator\ValidationErrors;

class Settings
{
    protected array $pageSettings = [];

    public function __construct(
        protected SettingsSchema $schema,
        protected int $currentPageID,
        protected array $mockPageData,
    ) {
    }

    /**
     * Returns a given setting on the the current page, or specified page.
     *
     * If the setting is not defined, an exception is thrown.
     *
     * @param string $key
     *   The settings key to retrieve.
     * @param int|null $pageId
     *   The ID of a page on which to get the setting, or null to use the current page.
     * @return mixed
     *   The value of the setting.
     */
    public function get(string $key, ?int $pageId = null): mixed
    {
        $pageId ??= $this->getCurrentPageId();

        return $this->getSettingForPage($key, $pageId)
            ?? $this->getSettingFromGlobal($key)
            ?? $this->getSettingFromDefaults($key)
            ?? throw SettingNotFound::create($key, $pageId);
    }

    protected function getSettingFromGlobal(string $key): mixed
    {
        return null;
    }

    /**
     * Gets a settings default value from the schema.
     *
     * @param string $key
     * @return mixed
     */
    protected function getSettingFromDefaults(string $key): mixed
    {
        return $this->schema->getDefinition($key)?->default;
    }

    /**
     * Returns a settings value in the context of the specified page.
     *
     * If necessary, this method will scan up the page tree for the settings
     * value set on that page.
     *
     * @param string $key
     * @param int $pageId
     * @return mixed
     */
    protected function getSettingForPage(string $key, int $pageId): mixed
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
    protected function loadSettingsForPage(int $pageId): array
    {
        // Replace with something that reads from the DB.
        return $this->mockPageData[$pageId]['settings'];
    }

    // @todo Make this real.
    protected function getParentPageId(int $pageId): int
    {
        return $this->mockPageData[$pageId]['parent'];
    }

    // @todo Make this real.
    protected function getCurrentPageId(): int
    {
        return $this->currentPageID;
    }

    /**
     * Sets a given value in the context of the specified page.
     *
     * If the value is not valid according to the settings schema, an exception
     * will be thrown.
     *
     * @param int $pageId
     * @param string $key
     * @param int|float|string|array $value
     */
    public function set(int $pageId, string $key, int|float|string|array $value): void
    {
        $this->setMultiple($pageId, [$key => $value]);
    }

    /**
     * Sets multiple values in the context of a given page.
     *
     * If the values are not valid according to the settings schema, an exception
     * will be thrown.
     *
     * @param int $pageId
     * @param array $values
     */
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
