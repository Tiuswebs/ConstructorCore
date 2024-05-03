<?php

namespace Tiuswebs\ConstructorCore\Components;

use Tiuswebs\ConstructorCore\QueryFields;
use Illuminate\Support\Str;
use Tiuswebs\ConstructorCore\Inputs\Text;

class Panel extends Component
{
	public $show_before, $values;
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
		return collect($this->getRawFields())->filter(function($item) {
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
		if($this->repeat === false) {
			return collect($fields);
		}

		$repeat = $this->repeat;
		if(is_string($repeat) && isset($this->values)) {
			$repeat = $this->values->$repeat ?? null;
		}
		if(!is_numeric($repeat)) {
			return collect($fields);
		}

		$panel_title = Str::slug($this->title_nt, '_');

		if($repeat == 0) {
			return collect([
				Text::make($this->title)->setColumn($panel_title)->setValue([])->show(false)
			]);
		}

		// Repeat fields
		$new_fields = collect([]);
		for($i=0; $i<$repeat; $i++) {
			$title = $this->title_nt.' '.$i;
			$data = [];
			foreach($this->column as $field) {
				if(isset($field->is_group) && $field->is_group) {
					$data[] = (clone $field)->setParentColumn($panel_title.'.'.$i);
				} else {
					$data[] = (clone $field)->setColumn($panel_title.'.'.$i.'.'.$field->column);	
				}
			}
			$new_fields[] = Panel::make($title, $data);
		}
		return $new_fields;
	}

	public function setValues($values)
	{
		$this->values = (object) collect($values)->all();
		return $this;
	}

	// This function is used by the constructor on /admin site to show the inputs to the client
	public function setChildColumns($name, $component)
	{
		$this->column = QueryFields::make($component, $this->column)->expandTypes()->get()->map(function($item) use ($name) {
			$column = $item->column;
			if(Str::contains($column, '.')) {
				$column = '['.str_replace('.', '][', $column).']';
				$column_name = explode('[', $column)[0];
				$column = str_replace($column_name, '['.$column_name.']', $column);
				$item->setColumn($name.$column);
				return $item;
			}
			$item->setColumn($name.'['.$column.']');
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
