<?php

namespace Tiuswebs\ConstructorCore\Tests\Inputs;

use Tiuswebs\ConstructorCore\Tests\TestCase;
use Tiuswebs\ConstructorCore\Helpers\MultiplePanels;
use Tiuswebs\ConstructorCore\Inputs\Text;

class MultiplePanelsTest extends TestCase
{
    /** @test */
    public function it_returns_text_value()
    {
        $fields = [
            MultiplePanels::make('Tab', 'Item', [
                Text::make('Title')->default('Support'),
                Text::make('Text')->default('910-784-8015'),
                Text::make('Link')->default('tel:910-784-8015'),
            ])->defaultPanels(3),
        ];
        $values = $this->getValuesFrom($fields);

        $this->assertTrue(isset($values->tab[0]));
        $this->assertEquals($values->tab[0]->items, 3);
        $this->assertEquals($values->tab[0]->title, 'Support');
    }
}