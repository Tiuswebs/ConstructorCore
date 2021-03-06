<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\TailwindClass;
use Tiuswebs\ConstructorCore\Inputs\BackgroundColor;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;
use Tiuswebs\ConstructorCore\Inputs\FontWeight;

class Badge extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->original_title.' Text')->default('Important'),
			TextColor::make($this->original_title.' Text Color')->default('#fff'),
			BackgroundColor::make($this->original_title.' Background Color')->default('#333'),
			TailwindClass::make($this->original_title.' Size')->default('text-base'),
			FontFamily::make($this->original_title.' Font'),
			FontWeight::make($this->original_title.' Weight'),
			TailwindClass::make($this->original_title.' Classes')->default(''),
		];
	}
}
