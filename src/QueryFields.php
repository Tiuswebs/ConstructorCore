<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\Text;

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

	public function expandPanels()
	{
		$this->fields = $this->fields->map(function($item) {
			if(isset($item->is_panel) && $item->is_panel) {
				return $item->column;
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
		$this->fillValues();
		return $this->fields;
	}

	public function getValues()
	{
		return $this->get()->mapWithKeys(function($item) {
			return [$item->column => $item->formatValue()];
		})->map(function($value) {
			return $this->core->replaceResults($value);
		});
	}

	/*
	 * Extra Stuff
	 */

	public function addExtraFields()
	{
		$this->fields = $this->fields->map(function($item) {
			if(get_class($item)=='Tiuswebs\ConstructorCore\Inputs\BelongsTo' && $item->show_id) {
				$input = $item->getProcessedInput($this->core);
				return [
					$input,
					Text::make($item->title_nt.' Id', $item->column.'_id')
				];
			} else if(get_class($item)=='Tiuswebs\ConstructorCore\Inputs\BelongsTo' && !$item->show_id) {
				return $item->getProcessedInput($this->core);
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
		if(!is_array($column) && isset($this->core->data->$column )) {
			return $item->setValue($this->core->data->$column)->setComponent($this->core);
		} else if (isset($item->is_group) && $item->is_group) {
			// Is a Type
			return $item->setValues($this->core->values);
		} else if (!is_array($column) && isset($this->core->data) && array_key_exists($column, collect($this->core->data)->toArray())) {
			return $item->setValue(null)->setComponent($this->core);
		} else if (!is_array($column) && !isset($this->core->data->$column)) {
			return $item->setValue($item->default_value)->setComponent($this->core);
		} 
		return $item;
	}

	/*
	 * Setters
	 */

	
}
