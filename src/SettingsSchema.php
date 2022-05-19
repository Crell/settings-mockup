<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\SettingsPrototype\SchemaType\SchemaType;
use Crell\SettingsPrototype\Validator\ValidationError;
use Crell\SettingsPrototype\Validator\Validator;

class SettingsSchema
{
    use Hydratable;

    /**
     * @var SettingDefinition[]
     */
    protected array $definitions = [];

    public function newDefinition(string $name, SchemaType $type, mixed $default): SettingDefinition
    {
        return $this->definitions[$name] = new SettingDefinition($name, $type, $default);
    }

    public function getDefinition(string $name): ?SettingDefinition
    {
        return $this->definitions[$name] ?? null;
    }

    public function setDefinition(string $key, SettingDefinition $def): static
    {
        $this->definitions[$key] = $def;
        return $this;
    }

    /**
     * @return ValidationError[]
     */
    public function validate(string $key, mixed $value): array
    {
        if ($def = $this->getDefinition($key)) {
            return array_filter(array_map(static fn(Validator $v) => $v->validate(($value)), $def->validators));
        }
        return [];
    }

    public function addSchema(callable $pass): static
    {
        $pass($this);

        return $this;
    }

    public function mergeSchema(self $schema): static
    {
        $this->definitions += $schema->definitions;

        return $this;
    }
}
