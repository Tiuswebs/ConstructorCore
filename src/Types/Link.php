<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;

class Link extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->title.' Text')->default('Click here'),
			Text::make($this->title.' Link')->default('#'),
			TextColor::make($this->title.' Color')->default('#fff'),
			TextColor::make($this->title.' Color Hover')->default('#000'),
		];
	}
}
