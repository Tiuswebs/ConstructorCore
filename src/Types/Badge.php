<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\BackgroundColor;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;

class Badge extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->title.' Text')->default('Important'),
			TextColor::make($this->title.' Text Color')->default('#fff'),
			BackgroundColor::make($this->title.' Background Color')->default('#333'),
		];
	}
}
