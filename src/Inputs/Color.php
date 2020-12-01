<?php

namespace Tiuswebs\ConstructorCore\Inputs;

class Color extends Input
{
	public function form()
	{
		$this->attributes['data-type'] = 'color';
		return \Form::text($this->column, $this->default_value, $this->attributes);
	}
}
