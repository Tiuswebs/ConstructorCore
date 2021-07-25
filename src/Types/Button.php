<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\TailwindClass;
use Tiuswebs\ConstructorCore\Inputs\BackgroundColor;
use Tiuswebs\ConstructorCore\Inputs\BorderColor;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;
use Tiuswebs\ConstructorCore\Inputs\FontWeight;

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
			BorderColor::make($this->original_title.' Border Color'),
			BorderColor::make($this->original_title.' Border Color Hover'),
			TailwindClass::make($this->original_title.' Size')->default('text-base'),
			FontFamily::make($this->original_title.' Font'),
			FontWeight::make($this->original_title.' Weight'),
			TailwindClass::make($this->original_title.' Classes')->default(''),
		];
	}
}
