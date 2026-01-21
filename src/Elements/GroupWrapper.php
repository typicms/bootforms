<?php

namespace TypiCMS\BootForms\Elements;

use TypiCMS\Form\Elements\Element;

class GroupWrapper
{
    protected FormGroup $formGroup;

    protected Element $target;

    public function __construct(FormGroup $formGroup)
    {
        $this->formGroup = $formGroup;
        $this->target = $formGroup->control();
    }

    public function render(): string
    {
        return $this->formGroup->render();
    }

    public function formText(string $text): self
    {
        $this->formGroup->formText($text);

        return $this;
    }

    public function floating(): self
    {
        $this->formGroup->floating();

        return $this;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function addGroupClass(string $class): self
    {
        $this->formGroup->addClass($class);

        return $this;
    }

    public function removeGroupClass(string $class): self
    {
        $this->formGroup->removeClass($class);

        return $this;
    }

    public function groupData(string $attribute, string $value): self
    {
        $this->formGroup->data($attribute, $value);

        return $this;
    }

    public function labelClass(string $class): self
    {
        $this->formGroup->label()->addClass($class);

        return $this;
    }

    public function hideLabel(): self
    {
        $this->labelClass('visually-hidden');

        return $this;
    }

    public function required(bool $conditional = true): self
    {
        if ($conditional) {
            $this->formGroup->label()->addClass('form-label-required');
        }

        call_user_func_array([$this->target, 'required'], [$conditional]);

        return $this;
    }

    public function inline(): self
    {
        $this->formGroup->inline();

        return $this;
    }

    public function group(): self
    {
        $this->target = $this->formGroup;

        return $this;
    }

    public function label(): self
    {
        $this->target = $this->formGroup->label();

        return $this;
    }

    public function control(): self
    {
        $this->target = $this->formGroup->control();

        return $this;
    }

    public function __call($method, $parameters): self
    {
        call_user_func_array([$this->target, $method], $parameters);

        return $this;
    }
}
