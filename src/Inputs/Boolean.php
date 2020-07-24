<?php

namespace Tiuswebs\ConstructorCore\Inputs;

class Boolean extends Input
{
	public function form()
	{
		return \Form::hidden($this->column, 0).\Form::checkbox($this->column, 1,  !is_null($this->default_value) ? $this->default_value == 1 : null);
	}

	public function getValue($object)
	{
		$value = parent::getValue($object);
		$value = $value==='--' ? false : $value;
		if($this->source=='index') {
			if($value) {
				return '<span style="color: #2cbb7d;">✔</span>';
			}
			return '<span style="color: #e74344;">✘</span>';
		}
		if($value) {
			return '<span style="color: #2cbb7d; padding-right: 7px;">✔</span> '.__('Yes');
		}
		return '<span style="color: #e74344; padding-right: 10px;">✘</span> '.__('No');
	}
}
