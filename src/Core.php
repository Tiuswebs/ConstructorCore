<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\Boolean;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;

abstract class Core
{
	public $base_namespace = 'Tiuswebs\Modules\Elements\\';
	public $have_background_color = true;
	public $have_paddings = true;
	public $have_container = false;
	public $contents = false;
	public $belongs_to_data = [];
	public $belongs_to_list = [];
	public $default_values = [];
	public $name = '';
	public $data;
	public $values;
	public $constructor;
	public $team;
	public $id;

	public function __construct($constructor = null) 
	{
		$this->id = rand();
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
		$value = view($this->view, $data)->render();
		$core = $this;
		return view('constructor::module-outer', compact('core', 'value', 'component'));
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

	public function getDefaults()
	{
		$default_values = collect([
			'background_color' => 'inherit',
			'padding_top' => '',
			'padding_bottom' => '',
			'padding_tailwind' => 'py-24',
		]);
		$values = $this->default_values;
		return $default_values->merge($values);
	}

	public function getAllFields()
	{
		$default_values = $this->getDefaults();
		$initial_fields = [];
		if($this->have_background_color) {
			$initial_fields[] = Text::make('Background Color')->default($default_values['background_color']);
		}
		if($this->have_paddings) {
			$initial_fields[] = Text::make('Padding Top')->default($default_values['padding_top']);
			$initial_fields[] = Text::make('Padding Bottom')->default($default_values['padding_bottom']);
		}
		if($this->have_container) {
			$initial_fields[] = Boolean::make('With Container')->default($default_values['with_container']);
		}
		$fields = collect($this->fields())->map(function($item) {
			if(get_class($item)=='Tiuswebs\ConstructorCore\Inputs\BelongsTo') {
				$item = $item->setComponent($this);
				return [
					$item,
					Text::make($item->title_nt.' Id', $item->column.'_id')
				];
			} else if(isset($item->is_group) && $item->is_group) {
				return $item->theFields();
			}
			return $item;
		});
		return collect($initial_fields)->merge($this->baseFields())->merge($fields)->whereNotNull()->flatten(1);
	}

	public function filterFields($name) 
	{
		return $this->getAllFields()->filter(function($item) use ($name) {
			return get_class($item)=='Tiuswebs\ConstructorCore\Inputs\\'.$name;
		})->mapWithKeys(function($item) {
			$column = $item->column;
			return [$column => $this->values->$column];
		});
	}

	public function getFields()
	{
		$fields = $this->getAllFields();
		return $fields->flatten()->map(function($item) {
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
