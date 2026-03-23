<?php

namespace TypiCMS\BootForms\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\Elements\InvalidFeedback;

#[CoversClass(InvalidFeedback::class)]
class InvalidFeedbackTest extends TestCase
{
    public function test_can_render_basic_invalid_feedback(): void
    {
        $formText = new InvalidFeedback('Email is required.');

        $expected = '<div class="invalid-feedback">Email is required.</div>';
        $result = $formText->render();
        $this->assertEquals($expected, $result);

        $formText = new InvalidFeedback('First name is required.');

        $expected = '<div class="invalid-feedback">First name is required.</div>';
        $result = $formText->render();
        $this->assertEquals($expected, $result);
    }
}
