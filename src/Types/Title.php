<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\TailwindClass;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;

class Title extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->title.' Text')->default('Title'),
			TextColor::make($this->title.' Color')->default('#111827'),
			TailwindClass::make($this->title.' Size')->default('text-3xl'),
			FontFamily::make($this->title.' Font'),
		];
	}
}
