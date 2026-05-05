@props(['label', 'name', 'uncheckedValue' => null, 'inline' => false])
@if (! is_null($uncheckedValue))
    {!! BootForm::hidden($name)->value($uncheckedValue) !!}
@endif
@php
    $control = $inline ? BootForm::inlineCheckbox($label, $name) : BootForm::checkbox($label, $name);
@endphp
{!! \TypiCMS\BootForms\ComponentSupport::apply($control, $attributes) !!}
