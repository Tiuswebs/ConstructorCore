<?php

namespace Tiuswebs\ConstructorCore\Components;

use Tiuswebs\ConstructorCore\QueryFields;
use Illuminate\Support\Str;

class Panel extends Component
{
	public $is_panel = true;
	public $repeat = false;
	
	public function load()
	{
		$this->show_before = count($this->fields()) > 0;
	}

	public function formHtml($use_front_view = false)
	{
		$panel = $this;
		return view('constructor::panel-form', compact('panel'));
	}

	public function showHtml($object)
	{
		$panel = $this;
		$field = $this->fields()->first();
		$is_input = is_object($field) ? $field->is_input : false;
		return view('constructor::panel', compact('panel', 'object', 'is_input'));
	}

	public function html()
	{
		$input = $this;
		$value = $this->showHtml(null);
		return view('constructor::input-outer', compact('value', 'input'))->render();
	}

	public function getValue($object)
	{
		return $this->fields()->map(function($item) use ($object) {
			return $item->showHtml($object);
		})->implode('');
	}

	public function form()
	{
		return $this->fields()->map(function($item) {
			return $item->formHtml();
		})->implode('');
	}

	private function filterFields($where, $model)
	{
		$where = $where=='update' ? 'edit' : $where;
		$where = $where=='store' ? 'create' : $where;
		return collect($this->column)->filter(function($item) {
			return isset($item);
		})->flatten()->map(function($item) {
			if(isset($item->is_group) && $item->is_group) {
				return $item->theFields();
			}
			return $item;
		})->flatten()->map(function($item) use ($model) {
			return $item->setDefaultValueFromAttributes($model);
		});
	}

	public function fields($model = null)
	{
		return $this->filterFields($this->source, $model);
	}

	public function getRawFields()
	{
		$fields = $this->column;
		if(!$this->repeat) {
			return $fields;
		}

		// Repeat fields
		$new_fields = [];
		$panel_title = Str::slug($this->title, '_');
		for($i=0; $i<$this->repeat; $i++) {
			foreach($this->column as $field) {
				$new_fields[] = (clone $field)->setColumn($panel_title.'['.$i.']['.$field->column.']');
			}
		}
		return $new_fields;
	}

	public function setChildColumns($name, $component)
	{
		$this->column = QueryFields::make($component, $this->column)->expandTypes()->get()->map(function($item) use ($name) {
			$item->setColumn($name.'['.$item->column.']');
			return $item;
		})->all();
		return $this;
	}

	public function repeat($repeat)
	{
		$this->repeat = $repeat;
		return $this;
	}
}
