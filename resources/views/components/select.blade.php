@props(['label', 'name', 'options' => []])
{!! \TypiCMS\BootForms\ComponentSupport::apply(BootForm::select($label, $name, $options), $attributes) !!}
