@props(['label', 'name', 'value' => null])
{!! \TypiCMS\BootForms\ComponentSupport::apply(BootForm::date($label, $name, $value), $attributes) !!}
