<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Illuminate\Support\Str;

class Icon extends Type
{
	public $main_field = 'icon';
	private $extra_class;

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
		$classes = $this->getValue('classes');
		$class = $this->copy_from ?? $this->column;
		$class = str_replace('_', '-', $class).'-class';
		if(Str::startsWith($icon, 'http')) {
			return '<img src="'.$icon.'" style="height: '.$height.'" class="'.$classes.' '.$class.' '.$this->extra_class.'" />';
		}
		return '<i class="'.$icon.' '.$classes.' '.$class.' '.$this->extra_class.'" style="font-size: '.$height.'"></i>';
	}

	public function addExtraClass($class)
	{
		$this->extra_class = $class;
		return $this;
	}
}
