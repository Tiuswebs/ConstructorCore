<?php

namespace Tiuswebs\ConstructorCore\Inputs;

use Illuminate\Support\Str;

class Items extends Textarea
{
	public function load()
	{
		$this->default_value = "List 1\nList 2\nList 3\nList 4";
	}
	
	public function formatValue()
	{
		$value = parent::formatValue();
		if(is_null($value)) {
			return collect([]);
		}
		return collect(explode("\n", $value))->filter(function($item) {
			return strlen($item) > 0;
		})->map(function($item) {
			return trim($item);
		});
	}
}
