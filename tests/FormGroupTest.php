<?php

namespace TypiCMS\BootForms\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\Elements\FormGroup;
use TypiCMS\Form\FormBuilder;

#[CoversClass(FormGroup::class)]
class FormGroupTest extends TestCase
{
    private FormBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new FormBuilder;
    }

    public function test_can_render_basic_form_group(): void
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function test_can_render_with_placeholder(): void
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->placeholder('email address');

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email" placeholder="email address"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function test_can_render_with_value(): void
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->value('example@example.com');

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email" value="example@example.com"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function test_can_render_with_default_value(): void
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->defaultValue('example@example.com');

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email" value="example@example.com"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function test_default_value_not_applied_if_already_a_value(): void
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->value('test@test.com')->defaultValue('example@example.com');

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email" value="test@test.com"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function test_can_render_with_form_text(): void
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->formText('Email is required.');

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email"><small class="form-text">Email is required.</small></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function test_can_include_html_in_labels(): void
    {
        $label = $this->builder->label('<span>Email</span>');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);

        $expected = '<div class="mb-3"><label class="form-label"><span>Email</span></label><input type="text" name="email"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }
}
