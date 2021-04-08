<?php

namespace Tiuswebs\ConstructorCore\Types;

use Illuminate\Support\Str;

class Type
{
	public $default;
	public $is_group = true;

	public function __construct($title = null, $column = null)
	{
		$this->original_title = $title;
		$this->title = __($title);
		$this->column = $column;
	}

	public static function make($title = null, $column = null) 
	{
		if(is_null($column) && !is_null($title) && is_string($title)) {
			$column = class_basename($title);
			$column = Str::snake($column);
		}

		return new static($title, $column);	
	}

	public function default($array)
	{
		$this->default = $array;
		return $this;
	}

	public function getDefault()
	{
		$default = $this->default;
		return $default;
	}

	public function theFields()
	{
		$defaults = $this->getDefault();
		return collect($this->fields())->map(function($item) use ($defaults) {
			$column = trim(str_replace($this->column, '', $item->column), '_');
			if(isset($defaults[$column])) {
				$item = $item->default($defaults[$column]);
			}
			return $item;
		});
	}
}
