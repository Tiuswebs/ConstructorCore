<?php

namespace Tiuswebs\ConstructorCore\Types;

use Illuminate\Support\Str;

class Type
{
	public $default;
	public $is_group = true;
	public $values;

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
		return collect($this->fields())->map(function($item) {
			$column_name = $this->column.'_';
			$default_column = class_basename($this->original_title);
			$default_column = Str::snake($default_column).'_';
			return $item->setColumn(str_replace($default_column, $column_name, $item->column));
		})->map(function($item) use ($defaults) {
			$column = trim(str_replace($this->column, '', $item->column), '_');
			if(isset($defaults[$column])) {
				$item = $item->default($defaults[$column]);
			};
			return $item;
		});
	}

	public function formatValue()
	{
		return null;
	}

	public function setValues($values)
	{
		$this->values = (object) $values->all();
		return $this;
	}

	public function getValue($name)
	{
		$column = $this->getColumnName($name);
		return $this->values->$column;
	}

	public function getColumnName($name)
	{
		return $this->column.'_'.$name;
	}

	public function getClassName($name)
	{
		return str_replace('_', '-', $this->getColumnName($name));
	}
}
