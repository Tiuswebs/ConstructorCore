<?php

namespace Tiuswebs\ConstructorCore\Tests\Inputs;

use Tiuswebs\ConstructorCore\Tests\TestCase;
use Tiuswebs\ConstructorCore\Components\Panel;
use Tiuswebs\ConstructorCore\Inputs\Text;

class PanelTest extends TestCase
{
    /** @test */
    public function normal_panel()
    {
        $fields = [
            Panel::make('Item', [
                Text::make('Title')->default('Support'),
                Text::make('Text')->default('910-784-8015'),
                Text::make('Link')->default('tel:910-784-8015'),
            ]),
        ];
        $values = $this->getValuesFrom($fields);

        $this->assertEquals($values->item->text, '910-784-8015');
        $this->assertEquals($values->item->title, 'Support');
    }

    /** @test */
    public function repeated_panel()
    {
        $fields = [
            Panel::make('Item', [
                Text::make('Title')->default('Support'),
                Text::make('Text')->default('910-784-8015'),
                Text::make('Link')->default('tel:910-784-8015'),
            ])->default(3),
        ];
        $values = $this->getValuesFrom($fields);

        $this->assertTrue(isset($values->item[0]));
        $this->assertEquals($values->item[0]->title, 'Support');
    }

    /** @test */
    public function array name()
    {
        $fields = [
            Panel::make('Item', [
                Text::make('Title')->default('Support'),
                Text::make('Text')->default('910-784-8015'),
                Text::make('Link')->default('tel:910-784-8015'),
            ])->setColumn('ejemplo.hola',
        ];
        $values = $this->getValuesFrom($fields);

        $this->assertTrue(isset($values->ejemplo));
        $this->assertTrue(isset($values->ejemplo->hola));
        $this->assertEquals($values->ejemplo->hola->text, '910-784-8015');
        $this->assertEquals($values->ejemplo->hola->title, 'Support');
    }
}