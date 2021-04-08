<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\BackgroundColor;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;

class Button extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->title.' Text')->default('Click here'),
			Text::make($this->title.' Link')->default('#'),
			TextColor::make($this->title.' Text Color')->default('#fff'),
			TextColor::make($this->title.' Text Color Hover')->default('#000'),
			BackgroundColor::make($this->title.' Background Color')->default('#333'),
			BackgroundColor::make($this->title.' Background Color Hover')->default('#eee'),
		];
	}
}
