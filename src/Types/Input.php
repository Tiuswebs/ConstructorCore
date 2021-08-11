<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\TailwindClass;
use Tiuswebs\ConstructorCore\Inputs\BackgroundColor;
use Tiuswebs\ConstructorCore\Inputs\BorderColor;
use Tiuswebs\ConstructorCore\Inputs\TextColor;

class Input extends Type
{
	public function fields() 
	{
		return [
			TextColor::make($this->original_title.' Text Color')->default('#fff'),
			BackgroundColor::make($this->original_title.' Background Color')->default('#333'),
			BorderColor::make($this->original_title.' Border Color'),
			TailwindClass::make($this->original_title.' Size')->default('text-base'),
			TailwindClass::make($this->original_title.' Classes')->default(''),
		];
	}
}
