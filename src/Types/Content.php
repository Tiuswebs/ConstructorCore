<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\Trix;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\TailwindClass;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;
use Tiuswebs\ConstructorCore\Inputs\FontWeight;

class Content extends Type
{
	public function fields() 
	{
		return [
			Trix::make($this->original_title.' Text')->default('<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis modi alias architecto commodi blanditiis voluptates quod ex ea, nisi, neque dicta nemo nulla, enim soluta perspiciatis quam animi et! Quasi.</p><br /><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis modi alias architecto commodi blanditiis voluptates quod ex ea, nisi, neque dicta nemo nulla, enim soluta perspiciatis quam animi et! Quasi.</p>'),
			TextColor::make($this->original_title.' Color')->default('#6B7280'),
			TailwindClass::make($this->original_title.' Size')->default('text-base'),
			FontFamily::make($this->original_title.' Font'),
			FontWeight::make($this->original_title.' Weight'),
			TailwindClass::make($this->original_title.' Classes')->default(''),
		];
	}
}
