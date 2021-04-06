<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;

abstract class Component
{
	public $base_namespace = 'Tiuswebs\Modules\Elements\\';
	public $name = '';
	public $data;
	public $values;
	public $constructor;
	public $contents = false;
	public $team;
	public $belongs_to_data = [];
	public $belongs_to_list = [];

	public function __construct($constructor = null) 
	{
		$this->name = str_replace($this->base_namespace, '', get_class($this));
		$this->constructor = $constructor;
		$this->loadTeam();
		$this->loadValues();
		$this->load();
		$this->loadBelongsToData();
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

	public function loadTeam()
	{
		$team = \Cache::remember('loadTeam', now()->addDay(), function() {
			$url = config('app.tiuswebs_api')."/api/example_data/teams";
	        return collect(json_decode(Http::get($url)->body()))->random();
		});
		$this->team = $team;
	}

	public function baseFields()
	{
		return [];
	}

	public function getFields()
	{
		$fields = collect($this->fields())->prepend($this->baseFields())->whereNotNull()->flatten(1);
		return $fields->map(function($item) {
			if(get_class($item)=='Tiuswebs\ConstructorCore\Inputs\BelongsTo') {
				$item = $item->setComponent($this);
				return [
					$item,
					Text::make($item->title_nt.' Id', $item->column.'_id')
				];
			}
			return $item;
		})->flatten()->map(function($item) {
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
		})->flatten()->mapWithKeys(function($item) {
			return [$item->column => $item->formatValue()];
		})->map(function($value) {
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

    public function useFont($font_column)
    {
    	$font = (new FontFamily)->getFonts()->firstWhere('slug', $this->values->$font_column);
    	if(!isset($font) || $font->slug=='inherit') {
    		return;
    	}
    	return view('constructor::font-insert', compact('font'));
    }

    private function replaceResults($value)
    {
    	return $value;
    }

    public function getBelongsToOptions($model, $column)
	{
		if(isset($this->belongs_to_list[$column])) {
			return $this->belongs_to_list[$column];
		}

		$relation = Str::plural(str_replace('_', ' ', Str::snake($model)));
        $url = config('app.tiuswebs_api')."/api/example_data/{$relation}";
        $elements = collect(json_decode(Http::get($url)->body()));
        $return = $elements->random($elements->count() > 10 ? 10 : $elements->count());

        $main_field = collect($return->first())->keys()[2];
        $this->belongs_to_data[$column] = $return;
        $return = $return->pluck($main_field, 'id');
        $this->belongs_to_list[$column] = $return;
        return $return;
	}

	public function loadBelongsToData()
	{
		collect($this->getFields())->filter(function($item) {
    		return get_class($item)=='Tiuswebs\ConstructorCore\Inputs\BelongsTo';
    	})->each(function($item) {
    		$column = $item->column;
    		$get = $column;
    		if(!isset($this->values->$column)) {
    			$get = $column.'_id';
    		}
    		if($this->values->$get=='contents') {
    			$result = $this->contents;
    		} else {
    			$result = $this->belongs_to_data[$column]->firstWhere('id', $this->values->$get);	
    		}
    		$this->$column = $result;
    	});
	}
}
