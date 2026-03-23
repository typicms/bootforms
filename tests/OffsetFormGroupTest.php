<?php

namespace TypiCMS\BootForms\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\Elements\OffsetFormGroup;
use TypiCMS\Form\FormBuilder;

#[CoversClass(OffsetFormGroup::class)]
class OffsetFormGroupTest extends TestCase
{
    private FormBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new FormBuilder;
    }

    public function test_renders_with_mb_3_row_wrapper(): void
    {
        $button = $this->builder->button('Submit');
        $group = new OffsetFormGroup($button, ['lg' => [2, 10]]);

        $result = $group->render();

        $this->assertStringContainsString('<div class="mb-3 row">', $result);
    }

    public function test_renders_control_inside_offset_column(): void
    {
        $button = $this->builder->button('Click Me')->addClass('btn')->addClass('btn-secondary');
        $group = new OffsetFormGroup($button, ['lg' => [2, 10]]);

        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><button type="button" class="btn btn-secondary">Click Me</button></div></div>';

        $this->assertEquals($expected, $group->render());
    }

    public function test_renders_with_custom_column_sizes(): void
    {
        $button = $this->builder->button('Click Me')->addClass('btn')->addClass('btn-secondary');
        $group = new OffsetFormGroup($button, ['lg' => [3, 9]]);

        $expected = '<div class="mb-3 row"><div class="col-lg-offset-3 col-lg-9"><button type="button" class="btn btn-secondary">Click Me</button></div></div>';

        $this->assertEquals($expected, $group->render());
    }

    public function test_renders_with_multiple_breakpoints(): void
    {
        $button = $this->builder->button('Click Me')->addClass('btn')->addClass('btn-secondary');
        $group = new OffsetFormGroup($button, ['xs' => [5, 7], 'lg' => [3, 9]]);

        $expected = '<div class="mb-3 row"><div class="col-xs-offset-5 col-xs-7 col-lg-offset-3 col-lg-9"><button type="button" class="btn btn-secondary">Click Me</button></div></div>';

        $this->assertEquals($expected, $group->render());
    }

    public function test_set_column_sizes_changes_offset_columns(): void
    {
        $button = $this->builder->button('Click Me')->addClass('btn')->addClass('btn-secondary');
        $group = new OffsetFormGroup($button, ['lg' => [2, 10]]);
        $group->setColumnSizes(['lg' => [4, 8]]);

        $expected = '<div class="mb-3 row"><div class="col-lg-offset-4 col-lg-8"><button type="button" class="btn btn-secondary">Click Me</button></div></div>';

        $this->assertEquals($expected, $group->render());
    }

    public function test_set_column_sizes_returns_self_for_chaining(): void
    {
        $button = $this->builder->button('Submit');
        $group = new OffsetFormGroup($button, ['lg' => [2, 10]]);

        $this->assertSame($group, $group->setColumnSizes(['lg' => [3, 9]]));
    }

    public function test_to_string_returns_rendered_output(): void
    {
        $button = $this->builder->button('Click Me')->addClass('btn')->addClass('btn-secondary');
        $group = new OffsetFormGroup($button, ['lg' => [2, 10]]);

        $expected = '<div class="mb-3 row"><div class="col-lg-offset-2 col-lg-10"><button type="button" class="btn btn-secondary">Click Me</button></div></div>';

        $this->assertEquals($expected, (string) $group);
    }

    public function test_magic_call_delegates_to_control(): void
    {
        $button = $this->builder->button('Click Me')->addClass('btn')->addClass('btn-secondary');
        $group = new OffsetFormGroup($button, ['lg' => [2, 10]]);
        $group->addClass('extra-class');

        $result = $group->render();

        $this->assertStringContainsString('class="btn btn-secondary extra-class"', $result);
    }

    public function test_magic_call_returns_self_for_chaining(): void
    {
        $button = $this->builder->button('Submit');
        $group = new OffsetFormGroup($button, ['lg' => [2, 10]]);

        $this->assertSame($group, $group->addClass('extra-class'));
    }
}
