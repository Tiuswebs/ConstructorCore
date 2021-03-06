<?php

namespace Tiuswebs\ConstructorCore\Inputs;

class Money extends Input
{
	public function form()
	{
		$this->attributes['step'] = '.01';
		return \Form::number($this->column, $this->default_value, $this->attributes);
	}

	public function getValue($object)
	{
		$value = parent::getValue($object);
		if(!is_numeric($value)) {
			return $value;
		}
		return '$'.number_format($value, 2);
	}

	public function formatValue()
	{
		$value = parent::formatValue();
		return number_format($value, 0);
	}
}
