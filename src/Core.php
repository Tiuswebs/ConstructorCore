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
		$values = $this->values;
		$data = compact('component', 'values');
		if(isset($this->constructor)) {
			$data = collect($data)->merge($this->constructor->data)->all();
		}
		$value = view($this->view, $data)->render();
		$value = $this->updateViewRender($value);
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

	// Get fields processed, shows the child of the types and extra inputs added automatically
	public function getProcessedFields($show_childs = true)
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
		
		$fields = $this->fields($show_childs);
		if($show_childs) {
			$fields = $this->showChildsOnFields($fields);
		}
		return collect($initial_fields)->merge($this->baseFields())->merge($fields)->whereNotNull()->flatten(1);
	}

	// Get all the fields in one line, no panels, no types, only direct inputs
	public function getAllFields()
	{
		return $this->getProcessedFields()->map(function($item) {
			if(isset($item->is_panel) && $item->is_panel) {
				return $this->showChildsOnFields($item->column);
			}
			return $item;
		})->flatten();
	}

	// Make modifications on fields
	public function showChildsOnFields($fields)
	{
		return collect($fields)->map(function($item) {
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
		})->flatten();
	}

	public function getFields($show_childs = true)
	{
		$fields = $this->getProcessedFields();
		return $fields->map(function($item) use ($show_childs) {
			$column = $item->column;
		
			if(!is_array($column) && isset($this->data->$column )) {
				return $item->setValue($this->data->$column);
			} else if (!is_array($column) && isset($this->data) && array_key_exists($column, collect($this->data)->toArray())) {
				return $item->setValue(null);
			} else if (!is_array($column) && !isset($this->data->$column)) {
				return $item->setValue($item->default_value);
			} elseif (isset($column)) {
				// if is a panel
				$value = $item->column;
				if($show_childs) {
					$value = $this->showChildsOnFields($value);
				}
				$item->column = $value->map(function($item) {
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

	public function getInlineStyles()
	{
		$styles = [];
		$styles[] = $this->getStylesByInput('TextColor', 'color');
		$styles[] = $this->getStylesByInput('BackgroundColor', 'background-color');
		$styles[] = $this->getStylesByInput('BorderColor', 'border-color');
		$styles = collect($styles)->flatten(1)->groupBy('class');
		return $styles;
	}

	public function getStylesByInput($name, $attribute = null) 
	{
		return $this->getAllFields()->filter(function($item) use ($name) {
			return get_class($item)=='Tiuswebs\ConstructorCore\Inputs\\'.$name;
		})->map(function($item) use ($attribute) {
			$name = $item->column;
			$value = $this->values->$name;
			$parent = $item->parent;
			$name = str_replace('_', '-', $name);

			$class = '#section-'.$this->id.' .'.$name.', #section-'.$this->id.' .'.$name.' > a';
			if(Str::contains($name, 'hover')) {
				$class = '#section-'.$this->id.' .'.$name.':hover, #section-'.$this->id.' .'.$name.' > a:hover';
			}
			if(isset($parent)) {
				$parent = str_replace('_', '-', $parent);
				$class = str_replace($name, $parent.'-class', $class);
			}
			return compact('name', 'value', 'attribute', 'class', 'parent');
		})->values();
	}

	public function loadValues()
	{
		// Get normal values
		$values = collect($this->getFields())->map(function($item) {
			if(!$item->is_panel) {
				return $item;
			}
			return $item->column;
		})->flatten()->mapWithKeys(function($item) {
			return [$item->column => $item->formatValue()];
		})->map(function($value) {
			return $this->replaceResults($value);
		});

		// Get only types to see if there extra data to put
		$types = collect($this->getProcessedFields(false))->map(function($item) {
			if(isset($item->is_panel) && $item->is_panel) {
				return $item->column;
			}
			return $item;
		})->flatten()->filter(function($item) {
			if(isset($item->is_group) && $item->is_group) {
				return true;
			}
			return false;
		})->mapWithKeys(function($item) use ($values) {
			return [$item->column => $item->setValues($values)->formatValue()];
		})->whereNotNull();

		$values = $values->merge($types)->all();
		$this->values = (object) $values;
	}

	public function getPaddingStyle()
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

	public function updateViewRender($html)
	{
		$values = $this->getStylesByInput('TailwindClass')->groupBy('parent')->mapWithKeys(function($item, $key) {
			return [$key.'-class' => $item->pluck('value')->whereNotNull()->filter(function($item) {
				return strlen($item) > 0;
			})->implode(' ')];
		});
		foreach ($values as $key => $value) {
			$before = ' ';
			$html = str_replace($before.$key, $before.$key.' '.$value, $html);
			$before = '"';
			$html = str_replace($before.$key, $before.$key.' '.$value, $html);
			$before = "'";
			$html = str_replace($before.$key, $before.$key.' '.$value, $html);
		}
		return $html;
	}
}
