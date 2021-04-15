<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\TailwindClass;
use Tiuswebs\ConstructorCore\Inputs\BackgroundColor;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;

class Button extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->original_title.' Text')->default('Click here'),
			Text::make($this->original_title.' Link')->default('#'),
			TextColor::make($this->original_title.' Text Color')->default('#fff'),
			TextColor::make($this->original_title.' Text Color Hover')->default('#000'),
			BackgroundColor::make($this->original_title.' Background Color')->default('#333'),
			BackgroundColor::make($this->original_title.' Background Color Hover')->default('#eee'),
			TailwindClass::make($this->original_title.' Classes')->default(''),
		];
	}
}
