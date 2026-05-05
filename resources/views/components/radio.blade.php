@props(['label', 'name', 'value' => null, 'inline' => false])
@php
    $control = $inline ? BootForm::inlineRadio($label, $name, $value) : BootForm::radio($label, $name, $value);
@endphp
{!! \TypiCMS\BootForms\ComponentSupport::apply($control, $attributes) !!}
