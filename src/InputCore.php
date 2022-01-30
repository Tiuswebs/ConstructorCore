<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Str;

class InputCore
{
	public static function make($title = null, $column = null, $extra = null) 
	{
		if(is_null($column) && !is_null($title) && is_string($title)) {
			$column = class_basename($title);
			$column = Str::snake($column);
		}

		$source = session('source');
		return new static($title, $column, $extra, $source);	
	}

	public function load()
	{
		//
	}

	public function loadCore($core)
	{
		return $this;
	}
}
