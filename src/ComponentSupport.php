<?php

namespace TypiCMS\BootForms;

use Illuminate\View\ComponentAttributeBag;

class ComponentSupport
{
    private const BOOLEAN_ATTRIBUTES = ['required', 'disabled', 'readonly', 'autofocus', 'multiple', 'checked', 'selected'];

    public static function apply(mixed $control, ComponentAttributeBag $attributes): mixed
    {
        foreach ($attributes->getAttributes() as $key => $value) {
            if (in_array($key, self::BOOLEAN_ATTRIBUTES, true)) {
                if ($value === false || $value === null) {
                    continue;
                }

                $control->{$key}();

                continue;
            }

            match ($key) {
                'class' => $control->addClass($value),
                'group-class' => $control->addGroupClass($value),
                'label-class' => $control->labelClass($value),
                'help' => $control->formText($value),
                default => $control->attribute($key, $value),
            };
        }

        return $control;
    }
}
