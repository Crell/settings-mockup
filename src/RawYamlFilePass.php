<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\Serde\Serde;
use Crell\SettingsPrototype\SchemaType\StringType;
use Symfony\Component\Yaml\Yaml;

class RawYamlFilePass
{
    public function __construct(private readonly string $filename) {}

    public function __invoke(SettingsSchema $schema): void
    {
        $array = Yaml::parseFile($this->filename);

        foreach ($array as $key => $def) {
            $schema->addSchema($this->makeDef($key, $def));
        }
    }

    protected function makeDef(string $key, array $def): callable
    {
        return static function (SettingsSchema $schema) use ($key, $def): void {
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

            if (isset($form['widget']['class'])) {
                $widgetArgs = $def['widget'];
                unset($widgetArgs['class']);
                $newDef->widget = new $form['widget']['class'](...$widgetArgs);
            }

            foreach ($def['validators'] ?? [] as $validator) {
                if (!isset($validator['class'])) {
                    continue;
                }
                $validatorArgs = $validator;
                unset($validatorArgs['class']);
                $newDef->validators[] = new $validator['class'](...$validatorArgs);
            }
        };
    }
}
