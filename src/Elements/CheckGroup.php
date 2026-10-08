<?php

namespace TypiCMS\BootForms\Elements;

use TypiCMS\Form\Elements\Element;
use TypiCMS\Form\Elements\Label;

class CheckGroup extends FormGroup
{
    public function __construct(protected Label $label, protected Element $control)
    {
        $this->addClass('form-field');
    }

    public function render(): string
    {
        $html = '<div';
        $html .= $this->renderAttributes();
        $html .= '>';
        $html .= $this->control;
        $html .= $this->label;
        $html .= $this->renderInvalidFeedback();
        $html .= $this->renderFormText();

        return $html.'</div>';
    }

    public function inline(): self
    {
        $this->addClass('d-inline-grid me-5');

        return $this;
    }

    public function control(): Element
    {
        return $this->control;
    }

    public function __call($method, $parameters): self
    {
        call_user_func_array([$this->control, $method], $parameters);

        return $this;
    }
}
