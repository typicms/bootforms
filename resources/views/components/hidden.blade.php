@props(['name', 'value' => null])
{!! BootForm::hidden($name)->value($value) !!}
