<?php

namespace TypiCMS\BootForms;

use TypiCMS\Form\Elements\FormOpen;

class BootForm
{
    protected BasicFormBuilder $builder;

    public function __construct(protected BasicFormBuilder $basicFormBuilder, protected HorizontalFormBuilder $horizontalFormBuilder) {}

    public function open(): FormOpen
    {
        $this->builder = $this->basicFormBuilder;

        return $this->builder->open();
    }

    public function openHorizontal(array $columnSizes): FormOpen
    {
        $this->horizontalFormBuilder->setColumnSizes($columnSizes);
        $this->builder = $this->horizontalFormBuilder;

        return $this->builder->open();
    }

    public function __call(string $method, array $parameters)
    {
        return call_user_func_array([$this->builder, $method], $parameters);
    }
}
