<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\Widgets;

/**
 * Widgets, or whatever we call them, are the HTML form element to use.
 * This could be compound to represent more complex things, like CsvField shown here,
 * or a complex multi-select pull box thingie.  These are really just typed collections
 * of properties.  Actually rendering the form, based on this input, is the job
 * of a different system.
 */
interface Widget
{
}