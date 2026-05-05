@props(['label', 'name', 'value' => null])
{!! \TypiCMS\BootForms\ComponentSupport::apply(BootForm::file($label, $name, $value), $attributes) !!}
