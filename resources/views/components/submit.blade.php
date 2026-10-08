@props(['value' => 'Submit', 'type' => 'btn-solid theme-primary'])
{!! \TypiCMS\BootForms\ComponentSupport::apply(BootForm::submit($value, $type), $attributes) !!}
