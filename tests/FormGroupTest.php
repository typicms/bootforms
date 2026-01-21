<?php

namespace TypiCMS\BootForms\Tests;

use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\Elements\FormGroup;
use TypiCMS\Form\FormBuilder;

/**
 * @internal
 *
 * @coversNothing
 */
class FormGroupTest extends TestCase
{
    public function setUp(): void
    {
        $this->builder = new FormBuilder();
    }

    public function testCanRenderBasicFormGroup()
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function testCanRenderWithPlaceholder()
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->placeholder('email address');

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email" placeholder="email address"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function testCanRenderWithValue()
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->value('example@example.com');

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email" value="example@example.com"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function testCanRenderWithDefaultValue()
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->defaultValue('example@example.com');

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email" value="example@example.com"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function testDefaultValueNotAppliedIfAlreadyAValue()
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->value('test@test.com')->defaultValue('example@example.com');

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email" value="test@test.com"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function testCanRenderWithFormText()
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->formText('Email is required.');

        $expected = '<div class="mb-3"><label class="form-label">Email</label><input type="text" name="email"><small class="form-text">Email is required.</small></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function testCanIncludeHtmlInLabels()
    {
        $label = $this->builder->label('<span>Email</span>');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);

        $expected = '<div class="mb-3"><label class="form-label"><span>Email</span></label><input type="text" name="email"></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }

    public function testCanRenderWithFloatingLabels()
    {
        $label = $this->builder->label('Email');
        $text = $this->builder->text('email');
        $formGroup = new FormGroup($label, $text);
        $formGroup->floating();

        $expected = '<div class="mb-3 form-floating"><input type="text" name="email"><label class="form-label">Email</label></div>';
        $result = $formGroup->render();
        $this->assertEquals($expected, $result);
    }
}
