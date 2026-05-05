@props(['label', 'name', 'value' => null])
{!! \TypiCMS\BootForms\ComponentSupport::apply(BootForm::email($label, $name, $value), $attributes) !!}
