<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\BelongsTo;

class MultimediaItem extends Component
{
	public $category = 'gallery';

    public function baseFields()
    {
        return [
            BelongsTo::make('Multimedia', 'gallery')->setTitle('Gallery'),
        ];
    }
}