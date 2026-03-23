<?php

namespace TypiCMS\BootForms\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\Elements\CheckGroup;
use TypiCMS\Form\FormBuilder;

#[CoversClass(CheckGroup::class)]
class CheckGroupTest extends TestCase
{
    private FormBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new FormBuilder;
    }

    public function test_renders_with_form_check_class(): void
    {
        $label = $this->builder->label('Accept')->addClass('form-check-label');
        $control = $this->builder->checkbox('accept');
        $checkGroup = new CheckGroup($label, $control);

        $result = $checkGroup->render();

        $this->assertEquals('<div class="form-check"><input type="checkbox" name="accept" value="1"><label class="form-check-label">Accept</label></div>', $result);
    }

    public function test_renders_control_before_label(): void
    {
        $label = $this->builder->label('Subscribe');
        $control = $this->builder->checkbox('subscribe');
        $checkGroup = new CheckGroup($label, $control);

        $result = $checkGroup->render();

        $this->assertStringContainsString('<input type="checkbox"', $result);
        $this->assertLessThan(
            strpos($result, '<label'),
            strpos($result, '<input type="checkbox"'),
            'Control must appear before label'
        );
    }

    public function test_inline_adds_form_check_inline_class(): void
    {
        $label = $this->builder->label('Accept')->addClass('form-check-label');
        $control = $this->builder->checkbox('accept');
        $checkGroup = new CheckGroup($label, $control);
        $checkGroup->inline();

        $result = $checkGroup->render();

        $this->assertEquals('<div class="form-check form-check-inline"><input type="checkbox" name="accept" value="1"><label class="form-check-label">Accept</label></div>', $result);
    }

    public function test_inline_returns_self_for_chaining(): void
    {
        $label = $this->builder->label('Accept');
        $control = $this->builder->checkbox('accept');
        $checkGroup = new CheckGroup($label, $control);

        $result = $checkGroup->inline();

        $this->assertSame($checkGroup, $result);
    }

    public function test_control_returns_the_control_element(): void
    {
        $label = $this->builder->label('Accept');
        $control = $this->builder->checkbox('accept');
        $checkGroup = new CheckGroup($label, $control);

        $this->assertSame($control, $checkGroup->control());
    }

    public function test_renders_with_invalid_feedback(): void
    {
        $label = $this->builder->label('Accept')->addClass('form-check-label');
        $control = $this->builder->checkbox('accept')->addClass('form-check-input');
        $checkGroup = new CheckGroup($label, $control);
        $checkGroup->invalidFeedback('You must accept the terms.');

        $result = $checkGroup->render();

        $this->assertEquals('<div class="form-check"><input type="checkbox" name="accept" value="1" class="form-check-input"><label class="form-check-label">Accept</label><div class="invalid-feedback">You must accept the terms.</div></div>', $result);
    }

    public function test_renders_with_form_text(): void
    {
        $label = $this->builder->label('Accept')->addClass('form-check-label');
        $control = $this->builder->checkbox('accept')->addClass('form-check-input');
        $checkGroup = new CheckGroup($label, $control);
        $checkGroup->formText('Required to continue.');

        $result = $checkGroup->render();

        $this->assertEquals('<div class="form-check"><input type="checkbox" name="accept" value="1" class="form-check-input"><label class="form-check-label">Accept</label><small class="form-text">Required to continue.</small></div>', $result);
    }

    public function test_magic_call_delegates_to_control(): void
    {
        $label = $this->builder->label('Accept');
        $control = $this->builder->checkbox('accept');
        $checkGroup = new CheckGroup($label, $control);
        $checkGroup->id('my-checkbox');

        $result = $checkGroup->render();

        $this->assertStringContainsString('id="my-checkbox"', $result);
    }

    public function test_magic_call_returns_self_for_chaining(): void
    {
        $label = $this->builder->label('Accept');
        $control = $this->builder->checkbox('accept');
        $checkGroup = new CheckGroup($label, $control);

        $result = $checkGroup->id('my-checkbox');

        $this->assertSame($checkGroup, $result);
    }

    public function test_add_class_on_group_itself(): void
    {
        $label = $this->builder->label('Accept');
        $control = $this->builder->checkbox('accept');
        $checkGroup = new CheckGroup($label, $control);
        $checkGroup->addClass('custom-class');

        $result = $checkGroup->render();

        $this->assertStringContainsString('class="form-check custom-class"', $result);
    }
}
