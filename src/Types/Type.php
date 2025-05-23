<?php

namespace Tiuswebs\ConstructorCore\Types;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Tiuswebs\ConstructorCore\Traits\UseCss;

/**
 * The Types are group of inputs that return many variables to use on the views
 */
class Type
{
    use UseCss;
    
	public $default, $original_title, $values, $copy_from, $title, $column, $column_new;
	public $is_group = true;
	public $main_field = 'text';
	public $ignore = [];
	public $only = [];
	public $default_classes = '';

	public function __construct($title = null, $column = null)
	{
		$this->original_title = $title;
		$this->title = __($title);
		$this->column = $column;
		$this->load();
	}

	public static function make($title = null, $column = null) 
	{
		if(is_null($column) && !is_null($title) && is_string($title)) {
			$column = class_basename($title);
			$column = Str::snake($column);
		}

		return new static($title, $column);	
	}

	public function load()
	{
		//
	}

	public function default($array)
	{
		$this->default = $array;
		return $this;
	}

	public function ignore($array)
	{
		if(!is_array($array)) {
			$array = [$array];
		}
		$this->ignore = $array;
		return $this;
	}

	public function only($array)
	{
		if(!is_array($array)) {
			$array = [$array];
		}
		$this->only = $array;
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
			if(isset($this->column_new)) {
				$column_name = $this->column_new.'.'.$this->column.'_';
			}
			$default_column = class_basename($this->original_title);
			$default_column = Str::snake($default_column).'_';
			$column = str_replace($default_column, $column_name, $item->column);
			$type = str_replace($column_name, '', $column);
			$type = str_replace(']', '', $type);

			// Ignore adding a column if set on ignore
			if(collect($this->ignore)->count() > 0) {
				if(in_array($type, $this->ignore)) {
					return;
				}
			}

			// Only use selected columns if set on only
			if(collect($this->only)->count() > 0) {
				if(!in_array($type, $this->only)) {
					return;
				}
			}
			return $item->setColumn($column)->setParent($this);
		})->whereNotNull()->map(function($item) use ($defaults) {
			$column = str_replace($this->column.'_', '', $item->original_column ?? $item->column);
			if(isset($defaults[$column])) {
				$item = $item->default($defaults[$column]);
			};

            // Inherits CSS styles
            if (\method_exists($item, 'cssType')) {
                $item->cssType($this->css_load);
            }

			return $item;
		});
	}

	public function formatValue()
	{
		return null;
	}

	// When we use copyFrom values then the values are modified from the original field

	public function setValues($values)
	{
		$this->values = (object) collect($values)->all();
		if(is_null($this->copy_from)) {
			return $this;
		}

		collect($this->values)->filter(function($item, $key) {
			return Str::startsWith($key, $this->copy_from.'_');
		})->filter(function($item) {
			return isset($item) && strlen($item)>0;
		})->mapWithKeys(function($item, $key) {
			$key = str_replace($this->copy_from.'_', $this->column.'_', $key);
			return [$key => $item];
		})->each(function($item, $key) {
			if(isset($this->column_new)) {
				$column = $this->column_new.'.'.$key;
				$values = (array) $this->values;
				Arr::set($values, $column, $item);
				$this->values = (object) $values;
			} elseif (!isset($this->values->$key)) {
				$this->values->$key = $item;	
			}
		});
		return $this;
	}

	public function defaultValue($column)
	{
		$field = $this->theFields()->firstWhere('column', $column);
		if(!is_object($field)) {
			$field = $this->theFields()->firstWhere('original_column', $column);
		}
		if(!is_object($field)) {
			return;
		}
		return $field->default_value;
	}

	public function getValue($name, $default = null)
	{
		$column = $this->getColumnName($name);
		$default = is_null($default) ? $this->defaultValue($column) : $default;
		$values = collect($this->values)->toArrayAll();

		// Get the correct value if there is set a column new
		if(isset($this->values) && isset($this->column_new)) {
			$column = $this->column_new.'.'.$column;
			$value = Arr::get($values, $column, '0000');
			if($value==='0000') {
				$value = $default;
			}
			return $value;
		}

		if (array_key_exists($column, $values)) {
			return $this->values->$column;
		}
		return $default;
	}

	public function setParentColumn($name)
	{
		$this->column_new = $name;
		return $this;
	}

	public function getColumnName($name)
	{
		return $this->column.'_'.$name;
	}

	public function getClassName($name)
	{
		if(is_null($this->getValue($name))) {
			return '';
		}
		return str_replace('_', '-', $this->getColumnName($name));
	}

	public function copyFrom($copy_from)
	{
		$this->copy_from = $copy_from;
		return $this;
	}

	public function getCssClassName()
	{
		$parent = str_replace('_', '-', $this->column);
		return $parent.'-class';
	}

	public function hideAutomatically()
	{
		// Ignore adding a column if set on ignore
		if(collect($this->ignore)->count() > 0) {
			return !in_array($this->main_field, $this->ignore);
		}

		// Only use selected columns if set on only
		if(collect($this->only)->count() > 0) {
			return in_array($this->main_field, $this->only);
		}
		
		return true;
	}

	public function loadCore($core)
	{
		return $this;
	}
}
