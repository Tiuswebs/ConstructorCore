<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Illuminate\Support\Str;

class Icon extends Type
{
	public function fields() 
	{
		return [
			Text::make($this->original_title.' Icon')->default('fa fa-book'),
			Text::make($this->original_title.' Height')->default('20px'),
			TextColor::make($this->original_title.' Color')->default('#335EEA'),
			Text::make($this->original_title.' Classes')->default(''),
		];
	}

	public function formatValue()
	{
		$icon = $this->getValue('icon');
		$height = $this->getValue('height', 'inherit');
		$color = $this->getClassName('color');
		$classes = $this->getValue('classes');
		if(Str::startsWith($icon, 'http')) {
			return '<img src="'.$icon.'" style="height: '.$height.'" class="'.$color.' '.$classes.'" />';
		}
		return '<i class="'.$icon.' '.$color.' '.$classes.'" style="font-size: '.$height.'"></i>';
	}
}
