<?php

namespace TypiCMS\BootForms;

use TypiCMS\BootForms\Elements\CheckGroup;
use TypiCMS\BootForms\Elements\GroupWrapper;
use TypiCMS\BootForms\Elements\HorizontalFormGroup;
use TypiCMS\BootForms\Elements\OffsetFormGroup;
use TypiCMS\Form\Elements\FormOpen;
use TypiCMS\Form\FormBuilder;

class HorizontalFormBuilder extends BasicFormBuilder
{
    public function __construct(protected FormBuilder $builder, protected array $columnSizes = ['lg' => [2, 10]]) {}

    public function setColumnSizes(array $columnSizes): self
    {
        $this->columnSizes = $columnSizes;

        return $this;
    }

    public function open(): FormOpen
    {
        return $this->builder->open();
    }

    protected function formGroup(string $label, string $name, mixed $control): GroupWrapper
    {
        $label = $this->builder->label($label)
            ->addClass($this->getLabelClass())
            ->addClass('col-form-label')
            ->forId($name);

        $control->id($name);

        $formGroup = new HorizontalFormGroup($label, $control, $this->getControlSizes());

        if ($this->builder->hasError($name)) {
            $formGroup->invalidFeedback($this->builder->getError($name));
            $control->addClass('is-invalid');
        }

        return $this->wrap($formGroup);
    }

    protected function getControlSizes(): array
    {
        $controlSizes = [];
        foreach ($this->columnSizes as $breakpoint => $sizes) {
            $controlSizes[$breakpoint] = $sizes[1];
        }

        return $controlSizes;
    }

    protected function getLabelClass(): string
    {
        $class = '';
        foreach ($this->columnSizes as $breakpoint => $sizes) {
            $class .= sprintf('col-%s-%s ', $breakpoint, $sizes[0]);
        }

        return trim($class);
    }

    public function button(string $value, ?string $name = null, string $type = 'btn-secondary'): OffsetFormGroup
    {
        $button = $this->builder->button($value, $name)->addClass('btn')->addClass($type);

        return new OffsetFormGroup($button, $this->columnSizes);
    }

    public function submit(string $value = 'Submit', string $type = 'btn-primary'): OffsetFormGroup
    {
        $button = $this->builder->submit($value)->addClass('btn')->addClass($type);

        return new OffsetFormGroup($button, $this->columnSizes);
    }

    public function checkbox(string $label, string $name): OffsetFormGroup
    {
        $control = $this->builder->checkbox($name);
        $checkGroup = $this->checkGroup($label, $name, $control);

        return new OffsetFormGroup($this->wrap($checkGroup), $this->columnSizes);
    }

    protected function checkGroup(string $label, string $name, mixed $control): CheckGroup
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

    public function radio(string $label, string $name, int|string|null $value = null): OffsetFormGroup
    {
        if (is_null($value)) {
            $value = $label;
        }

        $control = $this->builder->radio($name, $value);
        $checkGroup = $this->checkGroup($label, $name, $control);

        return new OffsetFormGroup($this->wrap($checkGroup), $this->columnSizes);
    }

    public function file(string $label, string $name, ?string $value = null): HorizontalFormGroup
    {
        $control = $this->builder->file($name)->value($value);
        $label = $this->builder->label($label)
            ->addClass($this->getLabelClass())
            ->addClass('col-form-label')
            ->forId($name);

        $control->id($name);

        $formGroup = new HorizontalFormGroup($label, $control, $this->getControlSizes());

        if ($this->builder->hasError($name)) {
            $formGroup->invalidFeedback($this->builder->getError($name));
            $control->addClass('is-invalid');
        }

        return $formGroup;
    }

    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->builder, $method], $parameters);
    }
}
