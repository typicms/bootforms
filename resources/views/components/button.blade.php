@props(['value', 'name' => null, 'type' => 'btn-secondary'])
{!! \TypiCMS\BootForms\ComponentSupport::apply(BootForm::button($value, $name, $type), $attributes) !!}
