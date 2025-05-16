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
			Font::make('GothamPro')->url('https://cdn.statically.io/gist/mfd/f3d96ec7f0e8f034cc22ea73b3797b59/raw/fad2f254369fb54250260077a4c87391a6280c52/gotham.css'),
			Font::make('Lato')->url('https://fonts.googleapis.com/css2?family=Lato:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Bebas Neue')->url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap'),
			Font::make('Texturina')->url('https://fonts.googleapis.com/css2?family=Texturina:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Montserrat')->url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Roboto')->url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Prompt')->url('https://fonts.googleapis.com/css2?family=Prompt:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Inter')->url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Mulish')->url('https://fonts.googleapis.com/css2?family=Mulish:wght@200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Poppins')->url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Raleway')->url('https://fonts.googleapis.com/css2?family=Raleway:wght@100;200;300;400;500;600;700;800;900&display=swap'),
			Font::make('Darker Grotesque')->url('https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@300;400;500;600;700;800;900&display=swap'),
			Font::make('Metal')->url('https://fonts.googleapis.com/css2?family=Metal&display=swap'),
			Font::make('Dancing Script')->url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap'),
			Font::make('Laila')->url('https://fonts.googleapis.com/css2?family=Laila:wght@300;400;500;600;700&display=swap'),
			Font::make('Arvo')->url('https://fonts.googleapis.com/css2?family=Arvo:wght@300;400;500;600;700&display=swap'),
			Font::make('Merriweather Sans')->url('https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@300;400;500;600;700;800&display=swap'),
			Font::make('Open Sans')->url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap'),
			Font::make('Bai Jamjuree')->url('https://fonts.googleapis.com/css2?family=Bai+Jamjuree:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap'),
			Font::make('Quicksand')->url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap'),
			Font::make('Noto Sans')->url('https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap'),
			Font::make('Bricolage Grotesque')->url('https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap'),
		]);
	}
}
