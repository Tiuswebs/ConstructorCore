<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\FontWeight;
use Tiuswebs\ConstructorCore\Inputs\TailwindClass;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;

class Link extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->original_title.' Text')->default('Click here'),
			Text::make($this->original_title.' Link')->default('#'),
			TextColor::make($this->original_title.' Color')->default('#fff'),
			TextColor::make($this->original_title.' Color Hover')->default('#000'),
			FontWeight::make($this->original_title.' Weight'),
			FontFamily::make($this->original_title.' Font'),
			TailwindClass::make($this->original_title.' Classes')->default(''),
		];
	}
}
