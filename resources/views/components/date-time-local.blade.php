@props(['label', 'name', 'value' => null])
{!! \TypiCMS\BootForms\ComponentSupport::apply(BootForm::dateTimeLocal($label, $name, $value), $attributes) !!}
