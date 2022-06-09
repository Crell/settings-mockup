<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;


use Crell\SettingsPrototype\SchemaType\SchemaType;
use Crell\SettingsPrototype\Validator\Validator;
use Crell\SettingsPrototype\Widgets\Widget;

/**
 * A setting definition is the schema for a single item.
 *
 * The item's name is a dotted string that happens to correspond to a
 * path in the settings tree. The definition composes a type object,
 * form field object (generic settings common to all types), widget (the actual HTML form field),
 * a default value, and a list of validators.
 */
class SettingDefinition
{
    use Hydratable;

    public FormField $form;

    public Widget $widget;

    public string $categoryId = '';

    /**
     * @var Validator[]
     */
    public array $validators = [];

    public function __construct(
        public string $name,
        SchemaType $type,
        public mixed $default,
    ) {
        $this->form = new FormField();
        // The main purpose of a type class is to provide a default widget
        // and validators.  These can both be overridden/added to, though.
        $this->widget = $type->defaultWidget();
        $this->validators = $type->defaultValidators();
    }
}
