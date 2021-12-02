<?php

namespace Tiuswebs\ConstructorCore\Inputs;

use Tiuswebs\ConstructorCore\ConstructorHelper;
use Tiuswebs\ConstructorCore\Traits\InputSetters;
use Tiuswebs\ConstructorCore\Traits\InputRules;
use Tiuswebs\ConstructorCore\Traits\WithWidth;
use Illuminate\Support\Str;

/**
 * Inputs are fields that may return a variable to use on the views
 */
class Input
{
	use InputSetters, InputRules, WithWidth;

	public $is_input = true;
	public $show = true;
	public $is_panel = false;
	public $form_before = '';
	public $form_after = '';
	public $data_classes = '';
	public $original_title;
	public $title_nt;
	public $title;
	public $set_title_executed = false;
	public $needs_to_be_on_panel = true;
	public $column;
	public $extra;
	public $source; 
	public $value;
	public $size;
	public $parent;
	public $component;

	public function __construct($title = null, $column = null, $extra = null, $source = null)
	{
		$this->original_title = $title;
		$this->title_nt = $title;
		$this->title = __($title);
		$this->column = $column;
		$this->extra = $extra;
		$this->source = $source;
		$this->load();
	}

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
		// Do nothing
	}

	public function setValue($value)
	{
		if(!is_string($value) && is_callable($value)) {
			$value = $value();
		}
		$this->value = $value;
		$this->default_value = $value;
		return $this;
	}

	public function setComponent($component)
	{
		$this->component = $component;
		return $this;
	}

	public function loadCore($core)
	{
		return $this;
	}
	

	// Value to show on CRUDS

	public function getValue($object)
	{
		if(isset($this->value)) {
			return $this->value;
		}
		if(!isset($object)) {
			return;
		}
		$column = $this->column;
		if(!is_string($column) && is_callable($column)) {
			$return = $column($object);
		} else {
			$return = $object->$column;	
		}
		$return = isset($return) && strlen($return)>0 ? $return : '--';
		return $return;
	}

	// Value to show on Panel

	public function getValueProcessed($object)
	{
		$return = $this->getValue($object);
		if(Str::startsWith($return, 'http') && !isset($this->link)) {
			$this->link = $return;
			$this->link_target = '_blank';
		}
		$link = $this->link;
		if(isset($link)) {
			$add = isset($this->link_target) ? ' target="'.$this->link_target.'"' : '';
			$return = "<a href='{$link}'{$add}>{$return}</a>";
		}
		if(isset($this->display_using) && is_callable($this->display_using) && $return!='--') {
			$function = $this->display_using;
			$return = $function($return);
		}
		return $return;
	}

	// Value to show on components view

	public function formatValue()
	{
		return $this->value ?? null;
	}

	public function form()
	{
		return;
	}

	public function show($result)
    {
    	if(!is_string($result) && is_callable($result)) {
            $result = $result();
        } 
        $this->show = $result;
        return $this;
    }

	public function formHtml($use_front_view = false)
	{
		if(!$this->show) {
			return;
		}
		$input = $this;
		$view = $use_front_view ? 'front::input-form' : 'constructor::input-form';
		$html = view($view, compact('input'))->render();
		return $this->form_before.$html.$this->form_after;
	}

	public function showHtml($object)
	{
		if(!$this->show) {
			return;
		}
		$input = $this;
		$html = view('constructor::input-show', compact('input', 'object'))->render();
		return $this->validateConditional($object) ? $html : null;
	}

	public function setColumn($column)
	{
		$this->original_column = $this->column;
		$this->column = $column;
		return $this;
	}

	public function setTitle($title)
	{
		$this->title_nt = $title;
		$this->title = __($title);
		$this->set_title_executed = true;
		return $this;
	}

	public function setParent($parent)
	{
		$this->parent = $parent;
		return $this;
	}

	public function size($size = null)
	{
		if(isset($this->attributes['style']) || is_null($size)) {
			return $this;
		}
		$this->size = $size;
		$this->attributes['style'] = 'width: '.$size.'px';
		return $this;
	}

	// In case there default attributes for the model
	public function setDefaultValueFromAttributes($model)
	{
		if($this->source!='create' || !is_null($this->default_value) || is_null($model)) {
			return $this;
		}
		$model = new $model;
		$attributes = $model->getAttributes();
		if(isset($attributes[$this->column])) {
			$this->default($attributes[$this->column]);
		}
		return $this;
	}

	/**
	 * Allow to edit the data passed to create function of the object, returns the request gotten
	 **/

	public function processData($data)
	{
		return $data;
	}

	public function processDataAfterValidation($data)
	{
		return $data;
	}

	/**
	 * Can add extra validation to inputs in case is needed
	 **/

	public function validate($data)
	{
		return;
	}

	/**
	 * Action that is executed bofore an object is removed
	 **/

	public function removeAction($object)
	{
		return;
	}
}
