@props(['value', 'name' => null, 'type' => 'btn-solid theme-secondary'])
{!! \TypiCMS\BootForms\ComponentSupport::apply(BootForm::button($value, $name, $type), $attributes) !!}
