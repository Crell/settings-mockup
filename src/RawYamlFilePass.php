<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\SettingsPrototype\SchemaType\StringType;
use Symfony\Component\Yaml\Yaml;

class RawYamlFilePass
{
    public function __construct(private readonly string $filename) {}

    public function __invoke(SettingsSchema $schema): void
    {
        $array = Yaml::parseFile($this->filename);

        foreach ($array['properties'] ?? [] as $key => $def) {
            $schema->setDefinition($key, $this->makeSetting($key, $def, $schema));
        }

        foreach ($array['categories'] ?? [] as $key => $def) {
            $schema->setCategory($key, $this->makeCategory($key, $def));
        }
    }

    protected function makeCategory(string $id, array $def): CategoryDefinition
    {
        if (empty($def['label'])) {
            // @todo Real error handling here.
            throw new \InvalidArgumentException('Category must have a label');
        }

        $newDef = new CategoryDefinition($id, $def['label']);

        foreach (['description', 'order'] as $prop) {
            if (isset($def[$prop])) {
                $newDef->$prop = $def[$prop];
            }
        }

        return $newDef;
    }

    protected function makeSetting(string $key, array $def, SettingsSchema $schema): SettingDefinition
    {
        $def['type']['class'] ??= StringType::class;
        $typeArgs = $def['type'];
        unset($typeArgs['class']);

        if (!isset($def['default'])) {
            // @todo Real error handling here.
            throw new \InvalidArgumentException('Default must be specified');
        }

        $newDef = $schema->newDefinition(
            name: $key,
            type: new $def['type']['class'](...$typeArgs),
            default: $def['default'],
        );

        foreach (['label', 'description', 'icon', 'help'] as $prop) {
            if (isset($def['form'][$prop])) {
                $newDef->form->$prop = $def['form'][$prop];
            }
        }

        if (isset($def['widget']['class'])) {
            $widgetArgs = $def['widget'];
            unset($widgetArgs['class']);
            $newDef->widget = new $def['widget']['class'](...$widgetArgs);
        }

        foreach ($def['validators'] ?? [] as $validator) {
            if (!isset($validator['class'])) {
                continue;
            }
            $validatorArgs = $validator;
            unset($validatorArgs['class']);
            $newDef->validators[] = new $validator['class'](...$validatorArgs);
        }

        // Ensure that the default is valid.  If it's not, don't
        // even allow it in the schema.
        if ($schema->validate($key, $newDef->default)) {
            throw DefaultValueNotValid::create($key, $newDef->default);
        }

        // @todo Do we want to make this an error, and require a category?
        // If so, how do we validate that it's a valid category when the
        // referenced category may be defined in a later file?
        $newDef->categoryId = $def['category'] ?? '';

        return $newDef;
    }
}
