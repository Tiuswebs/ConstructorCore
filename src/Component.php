<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Str;

abstract class Component
{
	public $base_namespace = 'Tiuswebs\Modules\Elements\\';
	public $name = '';
	public $data;
	public $values;
	public $constructor;
	public $contents = false;

	public function __construct($constructor = null) 
	{
		$this->name = str_replace($this->base_namespace, '', get_class($this));
		$this->constructor = $constructor;
		$this->loadValues();
		$this->load();
		return;
	}

	public function render()
	{
		$component = $this;
		$data =compact('component');
		if(isset($this->constructor)) {
			$data = collect($data)->merge($this->constructor->data)->all();
		}
		return view($this->view, $data);
	}

	public function load()
	{
		//
	}

	public function getFields()
	{
		return collect($this->fields())->flatten(1)->map(function($item) {
			$column = $item->column;
		
			if(!is_array($column) && isset($this->data->$column )) {
				return $item->setValue($this->data->$column);
			} else if (!is_array($column) && isset($this->data) && array_key_exists($column, collect($this->data)->toArray())) {
				return $item->setValue(null);
			} else if (!is_array($column) && !isset($this->data->$column)) {
				return $item->setValue($item->default_value);
			} elseif (isset($column)) {
				$item->column = collect($item->column)->map(function($item) {
					$column = $item->column;
					if(isset($this->data->$column)) {
						$item->setValue($this->data->$column);
					} else {
						$item->setValue($item->default_value);
					}
					return $item;
				});
				return $item;
			}
			return $item;
		})->all();
	}

	public function loadValues()
	{
		$values = (object) collect($this->getFields())->map(function($item) {
			if(!$item->is_panel) {
				return $item;
			}
			return $item->column;
		})->flatten()->pluck('value', 'column')->map(function($value) {
			return $this->replaceResults($value);
		})->all();
		$this->values = $values;
	}

	public function getStyle()
    {
        $styles = ['padding_top', 'padding_bottom'];
        return collect($this->values)->filter(function($item, $key) use ($styles) {
            return in_array($key, $styles) && isset($item) && strlen($item)>0;
        })->map(function($item, $key) {
            $key = str_replace('_', '-', $key);
            return $key.': '.$item.' !important';
        })->implode('; ');
    }

    private function replaceResults($value)
    {
    	if(!Str::contains($value, '{')) {
    		return $value;
    	}

    	// Show attributes based on controller
		$data = collect($this->getAllData())->filter(function($item) {
			return is_string($item);
		});
		foreach ($data as $key => $replace) {
			$value = str_replace('{'.$key.'}', $replace, $value);
		}

    	// Show attributes from page object
    	$object = $this->getMainObject();
		$data = is_object($object) ? collect($object->toArray())->filter(function($item) {
			return !is_object($item) && !is_array($item);
		}) : [];
		foreach ($data as $key => $replace) {
			$value = str_replace('{'.$key.'}', $replace, $value);
		}

		return $value;
    }
}
