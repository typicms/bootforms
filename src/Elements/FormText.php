<?php

declare(strict_types=1);

namespace TypiCMS\BootForms\Elements;

use TypiCMS\Form\Elements\Element;

class FormText extends Element
{
    public function __construct(private readonly string $message)
    {
        $this->addClass('form-text');
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
