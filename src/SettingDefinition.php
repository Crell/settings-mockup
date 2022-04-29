<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

// A setting definition is the schema for a single item.  The item's name is a dotted string
// that happens to correspond to a path in the settings tree.
// The definition composes a type object, form field object (generic settings common to all types)
// widget (the actual HTML form field), a default value, and a list of validators.
use Crell\SettingsPrototype\SchemaType\SchemaType;
use Crell\SettingsPrototype\Validator\Validator;
use Crell\SettingsPrototype\Widgets\Widget;

class SettingDefinition
{
    public FormField $form;

    public Widget $widget;

    // A /-delimited string.
//    public string $category = '';

//    public array $tags = [];

    /**
     * @var Validator[]
     */
    public array $validators = [];

    public function __construct(
        public string $name,
        public SchemaType $type,
        public mixed $default,
    ) {
        $this->form = new FormField();
        // The main purpose of a type class is to provide a default widget
        // and validators.  These can both be overridden/added to, though.
        $this->widget = $type->defaultWidget();
        $this->validators = $this->type->defaultValidators();
    }
}


// Inheritance should be used sparingly, but in some cases it's the best tool.

// In this case it's better to not extend StringType and just return similar
// validators.

// The FormField is a collection of the generic form bits.  Mostly this is
// drawn from the existing constants form comment stuff.
