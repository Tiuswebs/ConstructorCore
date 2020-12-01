<?php

namespace Tiuswebs\ConstructorCore\Inputs;

use Tiuswebs\ConstructorCore\Font;

class FontFamily extends Input
{
	public function getFonts()
	{
		return collect([
			Font::make('Inherit'),
			Font::make('Great Vibes')->url('https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap'),
			Font::make('GothamPro')->url('https://cdn.rawgit.com/mfd/f3d96ec7f0e8f034cc22ea73b3797b59/raw/856f1dbb8d807aabceb80b6d4f94b464df461b3e/gotham.css'),
		]);
	}

	public function form()
	{
		return \Form::select($this->column, $this->getFonts()->pluck('title', 'slug'), $this->default_value, $this->attributes);
	}
}
