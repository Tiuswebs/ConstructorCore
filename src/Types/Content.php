<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\Trix;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;

class Content extends Type
{
	public function fields() 
	{
		return [
			Trix::make($this->title.' Text')->default('<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis modi alias architecto commodi blanditiis voluptates quod ex ea, nisi, neque dicta nemo nulla, enim soluta perspiciatis quam animi et! Quasi.</p><br /><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis modi alias architecto commodi blanditiis voluptates quod ex ea, nisi, neque dicta nemo nulla, enim soluta perspiciatis quam animi et! Quasi.</p>'),
			TextColor::make($this->title.' Color')->default('#6B7280'),
			Text::make($this->title.' Size')->default('text-base'),
		];
	}
}
