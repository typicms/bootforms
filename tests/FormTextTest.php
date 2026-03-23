<?php

namespace TypiCMS\BootForms\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\Elements\FormText;

#[CoversClass(FormText::class)]
class FormTextTest extends TestCase
{
    public function test_can_render_basic_form_text(): void
    {
        $formText = new FormText('Email is required.');

        $expected = '<small class="form-text">Email is required.</small>';
        $result = $formText->render();
        $this->assertEquals($expected, $result);

        $formText = new FormText('First name is required.');

        $expected = '<small class="form-text">First name is required.</small>';
        $result = $formText->render();
        $this->assertEquals($expected, $result);
    }
}
