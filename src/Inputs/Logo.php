<?php

namespace Tiuswebs\ConstructorCore\Inputs;

use Illuminate\Support\Str;

class Logo extends Select
{
	public function load()
	{
		$this->options = [
			'photo_url' => __('Main Logo'),
			'white_logo_url' => __('White Logo'),
		];
	}

	public function formatValue()
	{
		$value = parent::formatValue();
		if(!is_null($value) && strlen($value)>0 && Str::startsWith($value, 'http')) {
			return $value;
		}
        if(!is_object($this->component->team)) {
        	return;
        }
		if(is_null($value)) {
			$value = 'photo_url';
		}
        return $this->component->team->$value;
	}
}
