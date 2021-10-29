<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\BelongsTo;
use Tiuswebs\ConstructorCore\Inputs\TailwindClass;

class Newsletter extends Core
{
	public $category = 'newsletter';

    public function baseFields()
    {
        return [
            BelongsTo::make('Newsletter List'),
        ];
    }
}