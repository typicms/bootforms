<?php

namespace TypiCMS\BootForms\Tests;

use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\HorizontalFormBuilder;
use TypiCMS\Form\ErrorStore\ErrorStoreInterface;
use TypiCMS\Form\FormBuilder;
use TypiCMS\Form\OldInput\OldInputInterface;

#[CoversClass(HorizontalFormBuilder::class)]
class HorizontalFormBuilderTest extends TestCase
{
    private HorizontalFormBuilder $form;

    private FormBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new FormBuilder;
        $this->form = new HorizontalFormBuilder($this->builder);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_form_open(): void
    {
        $expected = '<form method="POST" action="">';
        $result = $this->form->open()->render();
        $this->assertEquals($expected, $result);
    }

    public function test_form_open_get(): void
    {
        $expected = '<form method="GET" action="">';
        $result = $this->form->open()->get()->render();
        $this->assertEquals($expected, $result);
    }

    public function test_form_open_custom_action(): void
    {
        $expected = '<form method="GET" action="/search">';
        $result = $this->form->open()->get()->action('/search')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_form_close(): void
    {
        $expected = '</form>';
        $result = $this->form->close();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_group(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="email">Email</label><div class="col-lg-10"><input type="text" name="email" class="form-control" id="email"></div></div>';
        $result = $this->form->text('Email', 'email')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_group_with_custom_widths(): void
    {
        $this->form->setColumnSizes(['lg' => [3, 9]]);
        $expected = '<div class="mb-3 row"><label class="col-lg-3 col-form-label form-label" for="email">Email</label><div class="col-lg-9"><input type="text" name="email" class="form-control" id="email"></div></div>';
        $result = $this->form->text('Email', 'email')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_group_with_multiple_breakpoint_sizes(): void
    {
        $this->form->setColumnSizes(['xs' => [5, 7], 'lg' => [3, 9]]);
        $expected = '<div class="mb-3 row"><label class="col-xs-5 col-lg-3 col-form-label form-label" for="email">Email</label><div class="col-xs-7 col-lg-9"><input type="text" name="email" class="form-control" id="email"></div></div>';
        $result = $this->form->text('Email', 'email')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_group_with_value(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="email">Email</label><div class="col-lg-10"><input type="text" name="email" class="form-control" id="email" value="example@example.com"></div></div>';
        $result = $this->form->text('Email', 'email')->value('example@example.com')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_group_with_attribute(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="email">Email</label><div class="col-lg-10"><input type="text" name="email" class="form-control" id="email" maxlength="50"></div></div>';
        $result = $this->form->text('Email', 'email')->attribute('maxlength', '50')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_group_with_error(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->andReturn('Email is required.');

        $this->builder->setErrorStore($errorStore);

        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="email">Email</label><div class="col-lg-10"><input type="text" name="email" class="form-control is-invalid" id="email"><div class="invalid-feedback">Email is required.</div></div></div>';
        $result = $this->form->text('Email', 'email')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_group_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->andReturn('example@example.com');

        $this->builder->setOldInputProvider($oldInput);

        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="email">Email</label><div class="col-lg-10"><input type="text" name="email" value="example@example.com" class="form-control" id="email"></div></div>';
        $result = $this->form->text('Email', 'email')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_group_with_old_input_and_default_value(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->andReturn('example@example.com');

        $this->builder->setOldInputProvider($oldInput);

        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="email">Email</label><div class="col-lg-10"><input type="text" name="email" value="example@example.com" class="form-control" id="email"></div></div>';
        $result = $this->form->text('Email', 'email')->defaultValue('test@test.com')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_group_with_default_value(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="email">Email</label><div class="col-lg-10"><input type="text" name="email" class="form-control" id="email" value="test@test.com"></div></div>';
        $result = $this->form->text('Email', 'email')->defaultValue('test@test.com')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_text_group_with_old_input_and_error(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->andReturn('example@example.com');

        $this->builder->setOldInputProvider($oldInput);

        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->andReturn('Email is required.');

        $this->builder->setErrorStore($errorStore);

        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="email">Email</label><div class="col-lg-10"><input type="text" name="email" value="example@example.com" class="form-control is-invalid" id="email"><div class="invalid-feedback">Email is required.</div></div></div>';
        $result = $this->form->text('Email', 'email')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_password_group(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="password">Password</label><div class="col-lg-10"><input type="password" name="password" class="form-control" id="password"></div></div>';
        $result = $this->form->password('Password', 'password')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_password_group_doesnt_keep_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->andReturn('password');

        $this->builder->setOldInputProvider($oldInput);

        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="password">Password</label><div class="col-lg-10"><input type="password" name="password" class="form-control" id="password"></div></div>';
        $result = $this->form->password('Password', 'password')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_password_group_with_error(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->andReturn('Password is required.');

        $this->builder->setErrorStore($errorStore);

        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="password">Password</label><div class="col-lg-10"><input type="password" name="password" class="form-control is-invalid" id="password"><div class="invalid-feedback">Password is required.</div></div></div>';
        $result = $this->form->password('Password', 'password')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_button(): void
    {
        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><button type="button" class="btn btn-secondary">Click Me</button></div></div>';
        $result = $this->form->button('Click Me')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_button_with_custom_column_sizes(): void
    {
        $this->form->setColumnSizes(['lg' => [3, 9]]);
        $expected = '<div class="mb-3 row"><div class="col-lg-offset-3 col-lg-9"><button type="button" class="btn btn-secondary">Click Me</button></div></div>';
        $result = $this->form->button('Click Me')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_button_with_multiple_breakpoint_sizes(): void
    {
        $this->form->setColumnSizes(['xs' => [5, 7], 'lg' => [3, 9]]);
        $expected = '<div class="mb-3 row"><div class="col-xs-offset-5 col-xs-7 col-lg-offset-3 col-lg-9"><button type="button" class="btn btn-secondary">Click Me</button></div></div>';
        $result = $this->form->button('Click Me')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_button_with_multiple_breakpoint_sizes_that_dont_fill_full_width(): void
    {
        $this->form->setColumnSizes(['xs' => [5, 7], 'lg' => [3, 6]]);
        $expected = '<div class="mb-3 row"><div class="col-xs-offset-5 col-xs-7 col-lg-offset-3 col-lg-6"><button type="button" class="btn btn-secondary">Click Me</button></div></div>';
        $result = $this->form->button('Click Me')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_submit(): void
    {
        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><button type="submit" class="btn btn-primary">Submit</button></div></div>';
        $result = $this->form->submit()->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_checkbox(): void
    {
        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><div class="form-check"><input type="checkbox" name="terms" value="1" id="terms" class="form-check-input"><label class="form-check-label" for="terms">Agree to Terms</label></div></div></div>';
        $result = $this->form->checkbox('Agree to Terms', 'terms')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_checkbox_with_error(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->andReturn('Must agree to terms.');

        $this->builder->setErrorStore($errorStore);

        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><div class="form-check"><input type="checkbox" name="terms" value="1" id="terms" class="form-check-input is-invalid"><label class="form-check-label" for="terms">Agree to Terms</label><div class="invalid-feedback">Must agree to terms.</div></div></div></div>';
        $result = $this->form->checkbox('Agree to Terms', 'terms')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_checkbox_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->andReturn('1');

        $this->builder->setOldInputProvider($oldInput);

        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><div class="form-check"><input type="checkbox" name="terms" value="1" id="terms" class="form-check-input" checked="checked"><label class="form-check-label" for="terms">Agree to Terms</label></div></div></div>';
        $result = $this->form->checkbox('Agree to Terms', 'terms')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_checkbox_checked(): void
    {
        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><div class="form-check"><input type="checkbox" name="terms" value="1" id="terms" class="form-check-input" checked="checked"><label class="form-check-label" for="terms">Agree to Terms</label></div></div></div>';
        $result = $this->form->checkbox('Agree to Terms', 'terms')->check()->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_checkbox_with_additional_attributes(): void
    {
        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><div class="form-check"><input type="checkbox" name="terms" value="1" id="terms" class="form-check-input" data-foo="bar"><label class="form-check-label" for="terms">Agree to Terms</label></div></div></div>';
        $result = $this->form->checkbox('Agree to Terms', 'terms')->attribute('data-foo', 'bar')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_radio(): void
    {
        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><div class="form-check"><input type="radio" name="color" value="red" id="color" class="form-check-input"><label class="form-check-label" for="color">Red</label></div></div></div>';
        $result = $this->form->radio('Red', 'color', 'red')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_radio_with_error(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->andReturn('Sample error');

        $this->builder->setErrorStore($errorStore);
        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><div class="form-check"><input type="radio" name="color" value="red" id="color" class="form-check-input is-invalid"><label class="form-check-label" for="color">Red</label><div class="invalid-feedback">Sample error</div></div></div></div>';
        $result = $this->form->radio('Red', 'color', 'red')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_radio_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->andReturn('red');

        $this->builder->setOldInputProvider($oldInput);

        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><div class="form-check"><input type="radio" name="color" value="red" id="color" class="form-check-input" checked="checked"><label class="form-check-label" for="color">Red</label></div></div></div>';
        $result = $this->form->radio('Red', 'color', 'red')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_textarea(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="bio">Bio</label><div class="col-lg-10"><textarea name="bio" rows="10" cols="50" class="form-control" id="bio"></textarea></div></div>';
        $result = $this->form->textarea('Bio', 'bio')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_textarea_with_rows(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="bio">Bio</label><div class="col-lg-10"><textarea name="bio" rows="5" cols="50" class="form-control" id="bio"></textarea></div></div>';
        $result = $this->form->textarea('Bio', 'bio')->rows(5)->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_textarea_with_cols(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="bio">Bio</label><div class="col-lg-10"><textarea name="bio" rows="10" cols="20" class="form-control" id="bio"></textarea></div></div>';
        $result = $this->form->textarea('Bio', 'bio')->cols(20)->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_textarea_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->andReturn('Sample bio');

        $this->builder->setOldInputProvider($oldInput);
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="bio">Bio</label><div class="col-lg-10"><textarea name="bio" rows="10" cols="50" class="form-control" id="bio">Sample bio</textarea></div></div>';
        $result = $this->form->textarea('Bio', 'bio')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_textarea_with_error(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->andReturn('Sample error');

        $this->builder->setErrorStore($errorStore);
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="bio">Bio</label><div class="col-lg-10"><textarea name="bio" rows="10" cols="50" class="form-control is-invalid" id="bio"></textarea><div class="invalid-feedback">Sample error</div></div></div>';
        $result = $this->form->textarea('Bio', 'bio')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_select(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="color">Favorite Color</label><div class="col-lg-10"><select name="color" class="form-select" id="color"><option value="1">Red</option><option value="2">Green</option><option value="3">Blue</option></select></div></div>';

        $options = ['1' => 'Red', '2' => 'Green', '3' => 'Blue'];
        $result = $this->form->select('Favorite Color', 'color', $options)->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_select_with_error(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->andReturn('Color is required.');

        $this->builder->setErrorStore($errorStore);

        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="color">Favorite Color</label><div class="col-lg-10"><select name="color" class="form-select is-invalid" id="color"><option value="1">Red</option><option value="2">Green</option><option value="3">Blue</option></select><div class="invalid-feedback">Color is required.</div></div></div>';

        $options = ['1' => 'Red', '2' => 'Green', '3' => 'Blue'];
        $result = $this->form->select('Favorite Color', 'color', $options)->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_select_with_old_input(): void
    {
        $oldInput = Mockery::mock(OldInputInterface::class);
        $oldInput->shouldReceive('hasOldInput')->andReturn(true);
        $oldInput->shouldReceive('getOldInput')->andReturn('2');

        $this->builder->setOldInputProvider($oldInput);

        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="color">Favorite Color</label><div class="col-lg-10"><select name="color" class="form-select" id="color"><option value="1">Red</option><option value="2" selected>Green</option><option value="3">Blue</option></select></div></div>';

        $options = ['1' => 'Red', '2' => 'Green', '3' => 'Blue'];
        $result = $this->form->select('Favorite Color', 'color', $options)->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_date_group(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="birthday">Birthday</label><div class="col-lg-10"><input type="date" name="birthday" class="form-control" id="birthday"></div></div>';
        $result = $this->form->date('Birthday', 'birthday')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_date_time_local_group(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="dob">Date & time of birth</label><div class="col-lg-10"><input type="datetime-local" name="dob" class="form-control" id="dob"></div></div>';
        $result = $this->form->dateTimeLocal('Date & time of birth', 'dob')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_file_group(): void
    {
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="file">File</label><div class="col-lg-10"><input type="file" name="file" id="file"></div></div>';
        $result = $this->form->file('File', 'file')->render();
        $this->assertEquals($expected, $result);
    }

    public function test_render_file_group_with_error(): void
    {
        $errorStore = Mockery::mock(ErrorStoreInterface::class);
        $errorStore->shouldReceive('hasError')->andReturn(true);
        $errorStore->shouldReceive('getError')->andReturn('Sample error');

        $this->builder->setErrorStore($errorStore);
        $expected = '<div class="mb-3 row"><label class="col-lg-2 col-form-label form-label" for="file">File</label><div class="col-lg-10"><input type="file" name="file" id="file" class="is-invalid"><div class="invalid-feedback">Sample error</div></div></div>';
        $result = $this->form->file('File', 'file')->render();
        $this->assertEquals($expected, $result);
    }
}
