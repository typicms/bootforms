<?php

namespace TypiCMS\BootForms\Tests;

use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\BasicFormBuilder;
use TypiCMS\BootForms\BootForm;
use TypiCMS\BootForms\HorizontalFormBuilder;
use TypiCMS\Form\ErrorStore\ErrorStoreInterface;
use TypiCMS\Form\FormBuilder;
use TypiCMS\Form\OldInput\OldInputInterface;

#[CoversClass(BootForm::class)]
class BootFormTest extends TestCase
{
    private BootForm $bootForm;

    private FormBuilder $formBuilder;

    protected function setUp(): void
    {
        $this->formBuilder = new FormBuilder;
        $basicBuilder = new BasicFormBuilder($this->formBuilder);
        $horizontalBuilder = new HorizontalFormBuilder($this->formBuilder);

        $this->bootForm = new BootForm($basicBuilder, $horizontalBuilder);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_open_returns_a_form_open_element(): void
    {
        $result = $this->bootForm->open()->render();

        $this->assertEquals('<form method="POST" action="">', $result);
    }

    public function test_open_switches_to_basic_builder(): void
    {
        $this->bootForm->open();
        $result = $this->bootForm->text('Email', 'email')->render();

        $this->assertEquals('<div class="mb-3"><label for="email" class="form-label">Email</label><input type="text" name="email" class="form-control" id="email"></div>', $result);
    }

    public function test_open_horizontal_returns_a_form_open_element(): void
    {
        $result = $this->bootForm->openHorizontal(['lg' => [2, 10]])->render();

        $this->assertEquals('<form method="POST" action="">', $result);
    }

    public function test_open_horizontal_switches_to_horizontal_builder(): void
    {
        $this->bootForm->openHorizontal(['lg' => [2, 10]]);
        $result = $this->bootForm->text('Email', 'email')->render();

        $this->assertEquals('<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="email">Email</label><div class="col-lg-10"><input type="text" name="email" class="form-control" id="email"></div></div>', $result);
    }

    public function test_open_horizontal_applies_custom_column_sizes(): void
    {
        $this->bootForm->openHorizontal(['lg' => [3, 9]]);
        $result = $this->bootForm->text('Email', 'email')->render();

        $this->assertEquals('<div class="mb-3 row"><label class="col-lg-3 col-form-label form-label" for="email">Email</label><div class="col-lg-9"><input type="text" name="email" class="form-control" id="email"></div></div>', $result);
    }

    public function test_close_renders_closing_form_tag(): void
    {
        $this->bootForm->open();
        $result = $this->bootForm->close();

        $this->assertEquals('</form>', $result);
    }

    public function test_magic_call_delegates_to_current_builder(): void
    {
        $this->bootForm->open();
        $result = $this->bootForm->text('Name', 'name')->render();

        $this->assertStringContainsString('<input type="text" name="name"', $result);
    }

    public function test_basic_builder_renders_text_group(): void
    {
        $this->bootForm->open();

        $expected = '<div class="mb-3"><label for="username" class="form-label">Username</label><input type="text" name="username" class="form-control" id="username"></div>';
        $result = $this->bootForm->text('Username', 'username')->render();

        $this->assertEquals($expected, $result);
    }

    public function test_basic_builder_renders_checkbox(): void
    {
        $this->bootForm->open();

        $expected = '<div class="form-check"><input type="checkbox" name="terms" value="1" id="terms" class="form-check-input"><label class="form-check-label" for="terms">Agree to Terms</label></div>';
        $result = $this->bootForm->checkbox('Agree to Terms', 'terms')->render();

        $this->assertEquals($expected, $result);
    }

    public function test_basic_builder_renders_submit_button(): void
    {
        $this->bootForm->open();

        $expected = '<button type="submit" class="btn btn-primary">Submit</button>';
        $result = $this->bootForm->submit()->render();

        $this->assertEquals($expected, $result);
    }

    public function test_horizontal_builder_renders_submit_with_offset(): void
    {
        $this->bootForm->openHorizontal(['lg' => [2, 10]]);

        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><button type="submit" class="btn btn-primary">Submit</button></div></div>';
        $result = $this->bootForm->submit()->render();

        $this->assertEquals($expected, $result);
    }

    public function test_horizontal_builder_renders_checkbox_with_offset(): void
    {
        $this->bootForm->openHorizontal(['lg' => [2, 10]]);

        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><div class="form-check"><input type="checkbox" name="terms" value="1" id="terms" class="form-check-input"><label class="form-check-label" for="terms">Agree to Terms</label></div></div></div>';
        $result = $this->bootForm->checkbox('Agree to Terms', 'terms')->render();

        $this->assertEquals($expected, $result);
    }

    public function test_basic_builder_shows_validation_errors(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->andReturn('Email is required.');

        $this->formBuilder->setErrorStore($errorStore);

        $this->bootForm->open();

        $expected = '<div class="mb-3"><label for="email" class="form-label">Email</label><input type="text" name="email" class="form-control is-invalid" id="email"><div class="invalid-feedback">Email is required.</div></div>';
        $result = $this->bootForm->text('Email', 'email')->render();

        $this->assertEquals($expected, $result);
    }

    public function test_horizontal_builder_shows_validation_errors(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->andReturn('Email is required.');

        $this->formBuilder->setErrorStore($errorStore);

        $this->bootForm->openHorizontal(['lg' => [2, 10]]);

        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="email">Email</label><div class="col-lg-10"><input type="text" name="email" class="form-control is-invalid" id="email"><div class="invalid-feedback">Email is required.</div></div></div>';
        $result = $this->bootForm->text('Email', 'email')->render();

        $this->assertEquals($expected, $result);
    }

    public function test_basic_builder_populates_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->andReturn('user@example.com');

        $this->formBuilder->setOldInputProvider($oldInput);

        $this->bootForm->open();

        $expected = '<div class="mb-3"><label for="email" class="form-label">Email</label><input type="text" name="email" value="user@example.com" class="form-control" id="email"></div>';
        $result = $this->bootForm->text('Email', 'email')->render();

        $this->assertEquals($expected, $result);
    }

    public function test_switching_from_horizontal_back_to_basic_on_reopen(): void
    {
        $this->bootForm->openHorizontal(['lg' => [2, 10]]);
        $horizontalResult = $this->bootForm->text('Email', 'email')->render();

        $this->bootForm->open();
        $basicResult = $this->bootForm->text('Email', 'email')->render();

        $this->assertStringContainsString('row', $horizontalResult);
        $this->assertStringNotContainsString('row', $basicResult);
    }
}
