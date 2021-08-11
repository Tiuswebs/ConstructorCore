<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\BelongsTo;
use Tiuswebs\ConstructorCore\Inputs\TailwindClass;

class Form extends Core
{
	public $category = 'form';
    public $input_classes = 'p-2';
    public $label_classes = 'pb-2';

    public function baseFields()
    {
        return [
            BelongsTo::make('Form'),
            TailwindClass::make('Form Input Classes')->default($this->input_classes)->show(false),
            TailwindClass::make('Form Label Classes')->default($this->label_classes)->show(false),
        ];
    }
}