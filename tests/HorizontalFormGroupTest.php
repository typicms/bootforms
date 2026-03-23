<?php

namespace TypiCMS\BootForms\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\Elements\HorizontalFormGroup;
use TypiCMS\Form\FormBuilder;

#[CoversClass(HorizontalFormGroup::class)]
class HorizontalFormGroupTest extends TestCase
{
    private FormBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new FormBuilder;
    }

    public function test_renders_with_row_class(): void
    {
        $label = $this->builder->label('Email')->forId('email')->addClass('col-lg-2')->addClass('col-form-label');
        $control = $this->builder->text('email')->id('email')->addClass('form-control');
        $group = new HorizontalFormGroup($label, $control, ['lg' => 10]);

        $result = $group->render();

        $this->assertStringContainsString('class="mb-3 row"', $result);
    }

    public function test_renders_label_before_control_wrapper(): void
    {
        $label = $this->builder->label('Email')->forId('email')->addClass('col-lg-2')->addClass('col-form-label');
        $control = $this->builder->text('email')->id('email')->addClass('form-control');
        $group = new HorizontalFormGroup($label, $control, ['lg' => 10]);

        $result = $group->render();

        $this->assertLessThan(
            strpos($result, '<div class="col-lg-10">'),
            strpos($result, '<label'),
            'Label must appear before the control wrapper div'
        );
    }

    public function test_renders_control_inside_column_div(): void
    {
        $label = $this->builder->label('Email')->forId('email')->addClass('col-lg-2')->addClass('col-form-label');
        $control = $this->builder->text('email')->id('email')->addClass('form-control');
        $group = new HorizontalFormGroup($label, $control, ['lg' => 10]);

        $expected = '<div class="mb-3 row"><label for="email" class="col-lg-2 col-form-label form-label">Email</label><div class="col-lg-10"><input type="text" name="email" id="email" class="form-control"></div></div>';

        $this->assertEquals($expected, $group->render());
    }

    public function test_renders_with_multiple_breakpoint_control_sizes(): void
    {
        $label = $this->builder->label('Email')->forId('email')->addClass('col-xs-5')->addClass('col-lg-3')->addClass('col-form-label');
        $control = $this->builder->text('email')->id('email')->addClass('form-control');
        $group = new HorizontalFormGroup($label, $control, ['xs' => 7, 'lg' => 9]);

        $expected = '<div class="mb-3 row"><label for="email" class="col-xs-5 col-lg-3 col-form-label form-label">Email</label><div class="col-xs-7 col-lg-9"><input type="text" name="email" id="email" class="form-control"></div></div>';

        $this->assertEquals($expected, $group->render());
    }

    public function test_renders_invalid_feedback_inside_control_column(): void
    {
        $label = $this->builder->label('Email')->forId('email')->addClass('col-lg-2')->addClass('col-form-label');
        $control = $this->builder->text('email')->id('email')->addClass('form-control')->addClass('is-invalid');
        $group = new HorizontalFormGroup($label, $control, ['lg' => 10]);
        $group->invalidFeedback('Email is required.');

        $result = $group->render();

        $this->assertStringContainsString('<div class="invalid-feedback">Email is required.</div>', $result);
        $controlDivPos = strpos($result, '<div class="col-lg-10">');
        $feedbackPos = strpos($result, '<div class="invalid-feedback">');
        $this->assertGreaterThan($controlDivPos, $feedbackPos, 'Invalid feedback must be inside the control column div');
    }

    public function test_renders_form_text_inside_control_column(): void
    {
        $label = $this->builder->label('Email')->forId('email')->addClass('col-lg-2')->addClass('col-form-label');
        $control = $this->builder->text('email')->id('email')->addClass('form-control');
        $group = new HorizontalFormGroup($label, $control, ['lg' => 10]);
        $group->formText('We will never share your email.');

        $result = $group->render();

        $this->assertStringContainsString('<small class="form-text">We will never share your email.</small>', $result);
        $controlDivPos = strpos($result, '<div class="col-lg-10">');
        $formTextPos = strpos($result, '<small class="form-text">');
        $this->assertGreaterThan($controlDivPos, $formTextPos, 'Form text must be inside the control column div');
    }

    public function test_add_class_on_group_affects_outer_div(): void
    {
        $label = $this->builder->label('Email')->forId('email');
        $control = $this->builder->text('email')->id('email');
        $group = new HorizontalFormGroup($label, $control, ['lg' => 10]);
        $group->addClass('extra-class');

        $result = $group->render();

        $this->assertStringContainsString('class="mb-3 row extra-class"', $result);
    }

    public function test_magic_call_delegates_to_control(): void
    {
        $label = $this->builder->label('Email')->forId('email')->addClass('col-lg-2')->addClass('col-form-label');
        $control = $this->builder->text('email')->id('email')->addClass('form-control');
        $group = new HorizontalFormGroup($label, $control, ['lg' => 10]);
        $group->value('test@example.com');

        $result = $group->render();

        $this->assertStringContainsString('value="test@example.com"', $result);
    }

    public function test_magic_call_returns_self_for_chaining(): void
    {
        $label = $this->builder->label('Email')->forId('email');
        $control = $this->builder->text('email')->id('email');
        $group = new HorizontalFormGroup($label, $control, ['lg' => 10]);

        $this->assertSame($group, $group->value('test@example.com'));
    }
}
