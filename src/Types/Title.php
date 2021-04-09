<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\BackgroundColor;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;

class Title extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->title.' Text')->default('Title'),
			TextColor::make($this->title.' Color')->default('#111827'),
			Text::make($this->title.' Size')->default('text-3xl'),
		];
	}
}
