<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\TailwindClass;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;
use Tiuswebs\ConstructorCore\Inputs\FontWeight;

class Paragraph extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->original_title.' Text')->default('Makes your pages look beautiful.'),
			TextColor::make($this->original_title.' Color')->default('#6B7280'),
			TailwindClass::make($this->original_title.' Size')->default('text-base'),
			FontFamily::make($this->original_title.' Font'),
			FontWeight::make($this->original_title.' Weight'),
			TailwindClass::make($this->original_title.' Classes')->default(''),
		];
	}
}
