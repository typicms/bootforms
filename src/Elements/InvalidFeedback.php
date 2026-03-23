<?php

namespace TypiCMS\BootForms\Elements;

use TypiCMS\Form\Elements\Element;

class InvalidFeedback extends Element
{
    public function __construct(private readonly string $message)
    {
        $this->addClass('invalid-feedback');
    }

    public function render(): string
    {
        $html = '<div';
        $html .= $this->renderAttributes();
        $html .= '>';
        $html .= $this->message;

        return $html.'</div>';
    }
}
