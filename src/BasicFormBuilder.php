<?php

namespace TypiCMS\BootForms;

use TypiCMS\BootForms\Elements\CheckGroup;
use TypiCMS\BootForms\Elements\FormGroup;
use TypiCMS\BootForms\Elements\GroupWrapper;
use TypiCMS\BootForms\Elements\HorizontalFormGroup;
use TypiCMS\BootForms\Elements\InputGroup;
use TypiCMS\BootForms\Elements\OffsetFormGroup;
use TypiCMS\Form\Elements\Element;
use TypiCMS\Form\FormBuilder;

class BasicFormBuilder
{
    public function __construct(protected FormBuilder $builder) {}

    protected function formGroup(string $label, string $name, mixed $control): GroupWrapper
    {
        $label = $this->builder->label($label)->forId($name);
        $control->id($name);

        $formGroup = new FormGroup($label, $control);

        if ($this->builder->hasError($name)) {
            $formGroup->invalidFeedback($this->builder->getError($name));
            $control->addClass('is-invalid');
        }

        return $this->wrap($formGroup);
    }

    protected function wrap(FormGroup $group): GroupWrapper
    {
        return new GroupWrapper($group);
    }

    public function text(string $label, string $name, ?string $value = null): GroupWrapper
    {
        $control = $this->builder->text($name)->value($value)->addClass('form-control');

        return $this->formGroup($label, $name, $control);
    }

    public function password(string $label, string $name): GroupWrapper
    {
        $control = $this->builder->password($name)->addClass('form-control');

        return $this->formGroup($label, $name, $control);
    }

    public function button(string $value, ?string $name = null, string $type = 'btn-secondary'): Element|OffsetFormGroup
    {
        return $this->builder->button($value, $name)->addClass('btn')->addClass($type);
    }

    public function submit(string $value = 'Submit', string $type = 'btn-primary'): Element|OffsetFormGroup
    {
        return $this->builder->submit($value)->addClass('btn')->addClass($type);
    }

    public function select(string $label, string $name, array $options = []): GroupWrapper
    {
        $control = $this->builder->select($name, $options)->addClass('form-select');

        return $this->formGroup($label, $name, $control);
    }

    public function checkbox(string $label, string $name): GroupWrapper|OffsetFormGroup
    {
        $control = $this->builder->checkbox($name);

        return $this->checkGroup($label, $name, $control);
    }

    public function inlineCheckbox(string $label, string $name): GroupWrapper
    {
        return $this->checkbox($label, $name)->inline();
    }

    protected function checkGroup(string $label, string $name, mixed $control): GroupWrapper|CheckGroup
    {
        $checkGroup = $this->buildCheckGroup($label, $name, $control);

        return $this->wrap($checkGroup);
    }

    protected function buildCheckGroup(string $label, string $name, mixed $control): CheckGroup
    {
        $label = $this->builder->label($label)->addClass('form-check-label')->forId($name);
        $control->id($name)->addClass('form-check-input');

        $checkGroup = new CheckGroup($label, $control);

        if ($this->builder->hasError($name)) {
            $checkGroup->invalidFeedback($this->builder->getError($name));
            $control->addClass('is-invalid');
        }

        return $checkGroup;
    }

    public function radio(string $label, string $name, ?string $value = null): GroupWrapper|OffsetFormGroup
    {
        if (is_null($value)) {
            $value = $label;
        }

        $control = $this->builder->radio($name, $value);

        return $this->radioGroup($label, $name, $control);
    }

    public function inlineRadio(string $label, string $name, ?string $value = null): GroupWrapper
    {
        return $this->radio($label, $name, $value)->inline();
    }

    protected function radioGroup(string $label, string $name, mixed $control): GroupWrapper
    {
        $checkGroup = $this->buildRadioGroup($label, $name, $control);

        return $this->wrap($checkGroup);
    }

    protected function buildRadioGroup(string $label, string $name, mixed $control): CheckGroup
    {
        $id = $name.'_'.mb_strtolower(trim((string) preg_replace('/[^A-Za-z0-9-]+/', '_', (string) $control->getAttribute('value'))));
        $label = $this->builder->label($label)->addClass('form-check-label')->forId($id);
        $control->id($id)->addClass('form-check-input');

        $checkGroup = new CheckGroup($label, $control);

        if ($this->builder->hasError($name)) {
            $checkGroup->invalidFeedback($this->builder->getError($name));
            $control->addClass('is-invalid');
        }

        return $checkGroup;
    }

    public function textarea(string $label, string $name): GroupWrapper
    {
        $control = $this->builder->textarea($name)->addClass('form-control');

        return $this->formGroup($label, $name, $control);
    }

    public function date(string $label, string $name, ?string $value = null): GroupWrapper
    {
        $control = $this->builder->date($name)->value($value)->addClass('form-control');

        return $this->formGroup($label, $name, $control);
    }

    public function dateTimeLocal(string $label, string $name, ?string $value = null): GroupWrapper
    {
        $control = $this->builder->dateTimeLocal($name)->value($value)->addClass('form-control');

        return $this->formGroup($label, $name, $control);
    }

    public function email(string $label, string $name, ?string $value = null): GroupWrapper
    {
        $control = $this->builder->email($name)->value($value)->addClass('form-control');

        return $this->formGroup($label, $name, $control);
    }

    public function file(string $label, string $name, ?string $value = null): GroupWrapper|HorizontalFormGroup
    {
        $control = $this->builder->file($name)->value($value);
        $label = $this->builder->label($label)->forId($name);
        $control->id($name)->addClass('form-control');

        $formGroup = new FormGroup($label, $control);

        if ($this->builder->hasError($name)) {
            $formGroup->invalidFeedback($this->builder->getError($name));
            $control->addClass('is-invalid');
        }

        return $this->wrap($formGroup);
    }

    public function inputGroup(string $label, string $name, ?string $value = null): GroupWrapper
    {
        $control = new InputGroup($name);
        if (! is_null($value) || ! is_null($value = $this->getValueFor($name))) {
            $control->value($value);
        }

        $control->addClass('form-control');

        return $this->formGroup($label, $name, $control);
    }

    public function number(string $label, string $name, ?string $value = null): GroupWrapper
    {
        $control = $this->builder->number($name)->value($value)->addClass('form-control');

        return $this->formGroup($label, $name, $control);
    }

    public function __call(string $method, array $parameters)
    {
        return call_user_func_array([$this->builder, $method], $parameters);
    }
}
