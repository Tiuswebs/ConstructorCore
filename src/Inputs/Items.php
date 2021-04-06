<?php

namespace Tiuswebs\ConstructorCore\Inputs;

use Illuminate\Support\Str;

class Items extends TextArea
{
	public function load()
	{
		$this->default_value = '<ul><li>List 1</li><li>List 2</li><li>List 3</li></ul>'
	}
	
	public function getValue($object)
	{
		$value = parent::getValue($object);
		return $value;
	}
}
