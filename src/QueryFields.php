<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\Text;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class QueryFields
{
	protected $core;
	protected $fields;

    public function __construct($core, $fields)
	{
		$this->core = $core;
		$this->fields = collect($fields);
	}

	public static function make($core, $fields) 
	{
		return new static($core, $fields);	
	}

	/*
	 * Generic
	 */

	public function typesAndPanels()
	{
		$this->fields = $this->fields->filter(function($item) {
			return (isset($item->is_group) && $item->is_group) || (isset($item->is_panel) && $item->is_panel);
		});
		return $this;
	}

	/*
	 * Types
	 */

	public function withoutTypes()
	{
		$this->fields = $this->fields->filter(function($item) {
			return !isset($item->is_group) || !$item->is_group;
		});
		return $this;
	}

	public function onlyTypes()
	{
		$this->fields = $this->fields->filter(function($item) {
			return isset($item->is_group) && $item->is_group;
		});
		return $this;
	}

	public function onlyTypesCalled($name)
	{
		$this->fields = $this->fields->filter(function($item) {
			return isset($item->is_group) && $item->is_group;
		})->filter(function($item) use ($name) {
			$class = get_class($item);
			$class = explode('\\', $class);
			$class = $class[count($class)-1];
			return  $name == $class;
		});
		return $this;
	}

	public function expandTypes()
	{
		$this->fields = $this->fields->map(function($item) {
			if(isset($item->is_group) && $item->is_group) {
				return $item->theFields();
			}
			return $item;
		})->flatten();
		return $this;
	}

	/*
	 * Panel
	 */

	public function withoutPanels()
	{
		$this->fields = $this->fields->filter(function($item) {
			return !isset($item->is_panel) || !$item->is_panel;
		});
		return $this;
	}

	public function onlyPanels()
	{
		$this->fields = $this->fields->filter(function($item) {
			return isset($item->is_panel) && $item->is_panel;
		});
		return $this;
	}

	public function onlyOnePanel()
	{
		$this->fields = $this->fields->map(function($item) {
			$has_childs = false;
			if(isset($item->is_panel) && $item->is_panel) {
				$items = $item->setValues($this->core->values)->getRawFields()->map(function($item) use (&$has_childs) {
					if(isset($item->is_panel) && $item->is_panel) {
						$has_childs = true;
					}
					return $item;
				})->flatten();
				if($has_childs) {
					return $items;
				}
			}
			return $item;
		})->flatten();
		return $this;
	}

	public function expandPanels()
	{
		$this->fields = $this->fields->map(function($item) {
			if(isset($item->is_panel) && $item->is_panel) {
				return $item->setValues($this->core->values)->getRawFields()->map(function($item) {
					if(isset($item->is_panel) && $item->is_panel) {
						return $item->setValues($this->core->values)->getRawFields();
					}
					return $item;
				})->flatten();
			}
			return $item;
		})->flatten();
		return $this;
	}

	public function expandPanelsWithRepeat($repeat = true)
	{
		$this->fields = $this->fields->map(function($item) use ($repeat) {
			$show = isset($item->is_panel) && $item->is_panel && (($item->repeat!==false && $repeat===true) || ($item->repeat===false && $repeat===false));
			if($show) {
				return $item->setValues($this->core->values)->getRawFields()->map(function($item) {
					if(isset($item->is_panel) && $item->is_panel) {
						return $item->setValues($this->core->values)->getRawFields();
					}
					return $item;
				})->flatten();
			}
			return $item;
		})->flatten();
		return $this;
	}

	/*
	 * General
	 */

	public function get()
	{
		$this->onlyOnePanel()->fillValues();
		return $this->fields;
	}

	public function getConstructor($key, $component)
	{
		$fields = $this->get()->map(function($field) use ($key, $component) {
			if(!$field->is_panel) {
				return $field->setColumn('components_data['.$key.'][data]['.$field->column.']');
			}
			return $field->setChildColumns('components_data['.$key.'][data]', $component);
		});
		return $fields;
	}

	public function getValues()
	{
		$values = [];

		// Save values
		$this->get()->each(function ($item) use (&$values) {
			$column = $item->column;
			if (isset($item->is_group) && $item->is_group && isset($item->column_new)) {
				$column = $item->column_new . '.' . $item->column;
			}
			if (isset($item->value)) {
				$item->setValue($this->core->replaceResults($item->value));
			}

			$value = $item->formatValue();

			Arr::set($values, $column, $value);
		});

		// Return values
		return collect($values);
	}

	/*
	 * Extra Stuff
	 */

	public function addExtraFields()
	{
		$this->fields = $this->fields->map(function($item) {
			$item = $item->loadCore($this->core);

			// Add extra input
			if(get_class($item)=='Tiuswebs\ConstructorCore\Inputs\BelongsTo' && $item->show_id) {
				return [
					$item,
					Text::make($item->title_nt.' Id', $item->column.'_id')
				];
			}
			return $item;
		})->flatten();
		return $this;
	}

	public function fillValues()
	{
		$this->addExtraFields();
		$this->fields = $this->fields->map(function($item) {
			// If is a panel
			if(isset($item->is_panel) && $item->is_panel) {
				$item->setComponent($this);
				$item->column = collect($item->column)->map(function($item) {
					return $this->fillValueToItem($item);
				});
				return $item;
			}
			$item = $this->fillValueToItem($item);
			return $item;
		});
		return $this;
	}

	private function fillValueToItem($item)
	{
		$column = $item->column;
		if(is_array($column)) {
			// It never access here according to tests
			return $item;
		} else if (isset($item->is_group) && $item->is_group) {
			// Is a Type
			return $item->setValues($this->core->values);
		}

		$data = collect($this->core->data)->toArrayAll(); // data
		$value = Arr::get($data, $column, '0000');
			
		if($value!=='0000') {
			return $item->setValue($value)->setComponent($this->core);
		}
		return $item->setValue($item->default_value)->setComponent($this->core);
	}
}
