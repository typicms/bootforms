<?php

namespace TypiCMS\BootForms;

use Illuminate\View\ComponentAttributeBag;

class ComponentSupport
{
    private const FLAG_METHODS = [
        'required' => 'required',
        'disabled' => 'disabled',
        'readonly' => 'readonly',
        'autofocus' => 'autofocus',
        'multiple' => 'multiple',
        'checked' => 'checked',
        'selected' => 'selected',
        'inline' => 'inline',
        'hide-label' => 'hideLabel',
    ];

    public static function apply(mixed $control, ComponentAttributeBag $attributes): mixed
    {
        foreach ($attributes->getAttributes() as $key => $value) {
            if (array_key_exists($key, self::FLAG_METHODS)) {
                if ($value === false || $value === null) {
                    continue;
                }

                $control->{self::FLAG_METHODS[$key]}();

                continue;
            }

            match ($key) {
                'class' => $control->addClass($value),
                'group-class' => $control->addGroupClass($value),
                'label-class' => $control->labelClass($value),
                'help' => $control->formText($value),
                'default-value' => $control->defaultValue($value),
                default => $control->attribute($key, $value),
            };
        }

        return $control;
    }
}
