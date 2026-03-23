<?php

namespace TypiCMS\BootForms\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TypiCMS\BootForms\Elements\InputGroup;
use TypiCMS\Form\Elements\Text;

#[CoversClass(InputGroup::class)]
class InputGroupTest extends TestCase
{
    public function test_can_render_basic_text(): void
    {
        $input = new InputGroup('email');
        $this->assertInstanceOf(Text::class, $input);

        $expected = '<div class="input-group"><input type="text" name="email"></div>';
        $result = $input->render();
        $this->assertEquals($expected, $result);
    }

    public function test_can_render_before_addon(): void
    {
        $input = new InputGroup('username');
        $this->assertEquals($input, $input->beforeAddon('@'));

        $expected = '<div class="input-group">@<input type="text" name="username"></div>';
        $result = $input->render();
        $this->assertEquals($expected, $result);
    }

    public function test_can_render_after_addon_and_type(): void
    {
        $input = new InputGroup('mail');
        $this->assertEquals($input, $input->type('email'));
        $this->assertEquals($input, $input->afterAddon('@domain.com'));

        $expected = '<div class="input-group"><input type="email" name="mail">@domain.com</div>';
        $result = $input->render();
        $this->assertEquals($expected, $result);
    }

    public function test_can_render_with_value(): void
    {
        $input = new InputGroup('test');
        $input = $input->value('abc');

        $expected = '<div class="input-group"><input type="text" name="test" value="abc"></div>';
        $result = $input->render();
        $this->assertEquals($expected, $result);

        $input = new InputGroup('test');
        $input = $input->value(null);

        $expected = '<div class="input-group"><input type="text" name="test"></div>';
        $result = $input->render();
        $this->assertEquals($expected, $result);
    }

    public function test_default_value(): void
    {
        $input = new InputGroup('test');
        $expected = '<div class="input-group"><input type="text" name="test" value="abc"></div>';
        $result = $input->defaultValue('abc')->render();
        $this->assertEquals($expected, $result);

        $input = new InputGroup('test');
        $expected = '<div class="input-group"><input type="text" name="test" value="xyz"></div>';
        $result = $input->value('xyz')->defaultValue('abc')->render();
        $this->assertEquals($expected, $result);

        $input = new InputGroup('test');
        $expected = '<div class="input-group"><input type="text" name="test" value="xyz"></div>';
        $result = $input->defaultValue('abc')->value('xyz')->render();
        $this->assertEquals($expected, $result);
    }
}
