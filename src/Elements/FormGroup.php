<?php

namespace TypiCMS\BootForms\Elements;

use TypiCMS\Form\Elements\Element;
use TypiCMS\Form\Elements\Label;

class FormGroup extends Element
{
    protected ?FormText $formText = null;

    protected ?InvalidFeedback $invalidFeedback = null;

    public function __construct(protected Label $label, protected Element $control)
    {
        $this->label->addClass('form-label');
        $this->addClass('mb-3');
    }

    public function render(): string
    {
        $html = '<div';
        $html .= $this->renderAttributes();
        $html .= '>';
        $html .= $this->label;
        $html .= $this->control;
        $html .= $this->renderInvalidFeedback();
        $html .= $this->renderFormText();

        return $html.'</div>';
    }

    public function formText(string $text): ?self
    {
        if ($this->formText instanceof FormText) {
            return null;
        }

        $this->formText = new FormText($text);

        return $this;
    }

    protected function renderFormText(): string
    {
        if ($this->formText instanceof FormText) {
            return $this->formText->render();
        }

        return '';
    }

    public function invalidFeedback(string $text): ?self
    {
        if ($this->invalidFeedback instanceof InvalidFeedback) {
            return null;
        }

        $this->invalidFeedback = new InvalidFeedback($text);

        return $this;
    }

    protected function renderInvalidFeedback(): string
    {
        if ($this->invalidFeedback instanceof InvalidFeedback) {
            return $this->invalidFeedback->render();
        }

        return '';
    }

    public function control(): Element
    {
        return $this->control;
    }

    public function label(): Label
    {
        return $this->label;
    }

    public function __call($method, $parameters): self
    {
        call_user_func_array([$this->control, $method], $parameters);

        return $this;
    }
}
