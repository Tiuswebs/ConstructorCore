<?php

namespace Tiuswebs\ConstructorCore\Inputs;

use Tiuswebs\ConstructorCore\Font;
use Tiuswebs\ConstructorCore\Traits\UseCss;

class FontFamily extends Select
{
	use UseCss;
	
	public function load()
	{
		$this->options($this->getFonts()->pluck('title', 'slug'));
	}

	public function getFonts()
	{
		return collect([
			Font::make('Great Vibes')->url('https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap'),
			Font::make('GothamPro')->url('https://cdn.rawgit.com/mfd/f3d96ec7f0e8f034cc22ea73b3797b59/raw/856f1dbb8d807aabceb80b6d4f94b464df461b3e/gotham.css'),
			Font::make('Lato')->url('https://fonts.googleapis.com/css2?family=Lato:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Bebas Neue')->url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap'),
			Font::make('Texturina')->url('https://fonts.googleapis.com/css2?family=Texturina:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Montserrat')->url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Roboto')->url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700;800;900&display=swap')
		]);
	}
}
