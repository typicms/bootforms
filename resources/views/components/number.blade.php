@props(['label', 'name', 'value' => null])
{!! \TypiCMS\BootForms\ComponentSupport::apply(BootForm::number($label, $name, $value), $attributes) !!}
