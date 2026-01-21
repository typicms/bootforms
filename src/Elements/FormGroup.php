<?php

namespace TypiCMS\BootForms\Elements;

use TypiCMS\Form\Elements\Element;
use TypiCMS\Form\Elements\Label;

class FormGroup extends Element
{
    protected Label $label;

    protected Element $control;

    protected ?FormText $formText = null;

    protected ?InvalidFeedback $invalidFeedback = null;

    protected bool $isFloating = false;

    public function __construct(Label $label, Element $control)
    {
        $this->label = $label;
        $this->label->addClass('form-label');
        $this->control = $control;
        $this->addClass('mb-3');
    }

    public function render(): string
    {
        $html = '<div';
        $html .= $this->renderAttributes();
        $html .= '>';
        if ($this->isFloating) {
            $html .= $this->control;
            $html .= $this->label;
        } else {
            $html .= $this->label;
            $html .= $this->control;
        }
        $html .= $this->renderInvalidFeedback();
        $html .= $this->renderFormText();
        $html .= '</div>';

        return $html;
    }

    public function floating(): ?self
    {
        $this->isFloating = true;
        $this->addClass('form-floating');

        return $this;
    }

    public function formText(string $text): ?self
    {
        if (isset($this->formText)) {
            return null;
        }
        $this->formText = new FormText($text);

        return $this;
    }

    protected function renderFormText(): string
    {
        if ($this->formText) {
            return $this->formText->render();
        }

        return '';
    }

    public function invalidFeedback(string $text): ?self
    {
        if (isset($this->invalidFeedback)) {
            return null;
        }
        $this->invalidFeedback = new InvalidFeedback($text);

        return $this;
    }

    protected function renderInvalidFeedback(): string
    {
        if ($this->invalidFeedback) {
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
