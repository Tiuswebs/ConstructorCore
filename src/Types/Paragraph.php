<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\TailwindClass;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;

class Paragraph extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->title.' Text')->default('Makes your pages look beautiful.'),
			TextColor::make($this->title.' Color')->default('#6B7280'),
			TailwindClass::make($this->title.' Size')->default('text-base'),
			FontFamily::make($this->title.' Font'),
		];
	}
}
