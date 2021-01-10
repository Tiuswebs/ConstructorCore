<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\BelongsTo;

class Form extends Component
{
	public $category = 'form';

    public function baseFields()
    {
        return [
            BelongsTo::make('Form'),
        ];
    }
}