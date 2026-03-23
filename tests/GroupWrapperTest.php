<?php

namespace TypiCMS\BootForms\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\Elements\FormGroup;
use TypiCMS\BootForms\Elements\GroupWrapper;
use TypiCMS\Form\FormBuilder;

#[CoversClass(GroupWrapper::class)]
class GroupWrapperTest extends TestCase
{
    private FormBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new FormBuilder;
    }

    private function makeWrapper(): GroupWrapper
    {
        $label = $this->builder->label('Email')->forId('email');
        $control = $this->builder->text('email')->id('email');
        $formGroup = new FormGroup($label, $control);

        return new GroupWrapper($formGroup);
    }

    public function test_render_delegates_to_form_group(): void
    {
        $wrapper = $this->makeWrapper();

        $expected = '<div class="mb-3"><label for="email" class="form-label">Email</label><input type="text" name="email" id="email"></div>';

        $this->assertEquals($expected, $wrapper->render());
    }

    public function test_to_string_returns_rendered_output(): void
    {
        $wrapper = $this->makeWrapper();

        $expected = '<div class="mb-3"><label for="email" class="form-label">Email</label><input type="text" name="email" id="email"></div>';

        $this->assertEquals($expected, (string) $wrapper);
    }

    public function test_add_group_class_adds_class_to_form_group(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->addGroupClass('custom-class');

        $this->assertStringContainsString('class="mb-3 custom-class"', $wrapper->render());
    }

    public function test_add_group_class_returns_self_for_chaining(): void
    {
        $wrapper = $this->makeWrapper();

        $this->assertSame($wrapper, $wrapper->addGroupClass('custom-class'));
    }

    public function test_remove_group_class_removes_class_from_form_group(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->removeGroupClass('mb-3');

        $this->assertStringNotContainsString('mb-3', $wrapper->render());
    }

    public function test_remove_group_class_returns_self_for_chaining(): void
    {
        $wrapper = $this->makeWrapper();

        $this->assertSame($wrapper, $wrapper->removeGroupClass('mb-3'));
    }

    public function test_group_data_adds_data_attribute_to_form_group(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->groupData('controller', 'my-controller');

        $this->assertStringContainsString('data-controller="my-controller"', $wrapper->render());
    }

    public function test_group_data_returns_self_for_chaining(): void
    {
        $wrapper = $this->makeWrapper();

        $this->assertSame($wrapper, $wrapper->groupData('foo', 'bar'));
    }

    public function test_label_class_adds_class_to_label(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->labelClass('fw-bold');

        $this->assertStringContainsString('class="form-label fw-bold"', $wrapper->render());
    }

    public function test_label_class_returns_self_for_chaining(): void
    {
        $wrapper = $this->makeWrapper();

        $this->assertSame($wrapper, $wrapper->labelClass('fw-bold'));
    }

    public function test_hide_label_adds_visually_hidden_class(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->hideLabel();

        $this->assertStringContainsString('visually-hidden', $wrapper->render());
    }

    public function test_hide_label_returns_self_for_chaining(): void
    {
        $wrapper = $this->makeWrapper();

        $this->assertSame($wrapper, $wrapper->hideLabel());
    }

    public function test_required_adds_required_attribute_to_control(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->required();

        $this->assertStringContainsString('required', $wrapper->render());
    }

    public function test_required_adds_required_class_to_label(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->required();

        $this->assertStringContainsString('form-label-required', $wrapper->render());
    }

    public function test_required_with_false_skips_required_markup(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->required(false);

        $result = $wrapper->render();

        $this->assertStringNotContainsString('form-label-required', $result);
        $this->assertStringNotContainsString('required', $result);
    }

    public function test_required_returns_self_for_chaining(): void
    {
        $wrapper = $this->makeWrapper();

        $this->assertSame($wrapper, $wrapper->required());
    }

    public function test_form_text_delegates_to_form_group(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->formText('Enter your email address.');

        $this->assertStringContainsString('<small class="form-text">Enter your email address.</small>', $wrapper->render());
    }

    public function test_form_text_returns_self_for_chaining(): void
    {
        $wrapper = $this->makeWrapper();

        $this->assertSame($wrapper, $wrapper->formText('Help text.'));
    }

    public function test_magic_call_delegates_to_control_by_default(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->value('test@example.com');

        $this->assertStringContainsString('value="test@example.com"', $wrapper->render());
    }

    public function test_magic_call_returns_self_for_chaining(): void
    {
        $wrapper = $this->makeWrapper();

        $this->assertSame($wrapper, $wrapper->value('test@example.com'));
    }

    public function test_group_switches_target_to_form_group(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->group()->addClass('group-class');

        $this->assertStringContainsString('class="mb-3 group-class"', $wrapper->render());
    }

    public function test_label_switches_target_to_label(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->label()->addClass('label-class');

        $this->assertStringContainsString('class="form-label label-class"', $wrapper->render());
    }

    public function test_control_switches_target_back_to_control(): void
    {
        $wrapper = $this->makeWrapper();
        $wrapper->group()->control()->value('switched-to-control');

        $this->assertStringContainsString('value="switched-to-control"', $wrapper->render());
    }
}
