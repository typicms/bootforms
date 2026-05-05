@props(['value' => 'Submit', 'type' => 'btn-primary'])
{!! \TypiCMS\BootForms\ComponentSupport::apply(BootForm::submit($value, $type), $attributes) !!}
