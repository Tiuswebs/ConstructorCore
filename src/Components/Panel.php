<?php

namespace Tiuswebs\ConstructorCore\Components;

class Panel extends Core
{
	public $is_panel = true;
	
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
		})->flatten()->map(function($item) use ($model) {
			return $item->setDefaultValueFromAttributes($model);
		});
	}

	public function fields($model = null)
	{
		return $this->filterFields($this->source, $model);
	}

	public function setChildColumns($name)
	{
		$this->column = collect($this->column)->map(function($item) use ($name) {
			$item->setColumn($name.'['.$item->column.']');
			return $item;
		})->all();
		return $this;
	}
}
