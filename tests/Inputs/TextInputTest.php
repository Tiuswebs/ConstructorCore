<?php

namespace Tiuswebs\ConstructorCore\Tests\Inputs;


use Tiuswebs\ConstructorCore\Tests\TestCase;
use Tiuswebs\ConstructorCore\Inputs\Text;

class TextInputTest extends TestCase
{
    /** @test */
    public function it_returns_text_value()
    {
        $fields = [
            Text::make('Title')->default('Default Value')
        ];
        $values = $this->getValuesFrom($fields);
        $this->assertTrue($values->title == 'Default Value');
    }

    /** @test */
    public function works_with_different_column_name()
    {
        $fields = [
            Text::make('Title', 'another_column_name')->default('Default Value')
        ];
        $values = $this->getValuesFrom($fields);
        $this->assertTrue($values->another_column_name == 'Default Value');
    }
}