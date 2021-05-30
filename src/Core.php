<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\Boolean;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;

abstract class Core
{
	public $have_background_color = true;
	public $have_paddings = true;
	public $have_container = false;
	public $contents = false;
	public $belongs_to_data = [];
	public $belongs_to_list = [];
	public $default_values = [];
	public $show_view = true;
	public $name = '';
	public $data;
	public $values;
	public $constructor;
	public $team;
	public $id;
	public $view;

	public function __construct($constructor = null) 
	{
		$this->id = rand();
		$this->name = str_replace('Tiuswebs\Modules\Elements\\', '', get_class($this));
		$this->name = str_replace('Tiuswebs\ModulesApproved\Elements\\', '', $this->name);
		if(is_null($this->view)) {
			$name = explode('\\', $this->name);
			$name[0] = strtolower($name[0]);
			$name[1] = str_replace('_', '-', Str::snake($name[1]));
			$name = implode('.', $name);
			$this->view = 'modules::'.$name;
		}

		$this->constructor = $constructor;
		$this->loadTeam();
		$this->loadValues();
		$this->loadBelongsToData();
		$this->load();
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
		if(!$this->show_view) {
			return;
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
		$team = \Cache::remember('loadTeamInfo', now()->addDay(), function() {
			$url = config('app.tiuswebs_api')."/api/example_data/teams";
			$teams = collect(json_decode(Http::get($url)->body()))->filter(function($item) {
				return Str::contains($item->photo_url, 'tiuswebs');
			});
	        return $teams->random();
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
			'with_container' => true,
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
		$fields = collect($initial_fields)->merge($this->baseFields())->merge($fields)->whereNotNull()->flatten(1);
		if($show_childs) {
			$fields = $this->showChildsOnFields($fields);
		}
		return $fields;
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

	// Get all the types fields
	public function getTypesFields()
	{
		return $this->getProcessedFields(false)->filter(function($item) {
			return isset($item->is_group) && $item->is_group;
		})->flatten();
	}

	// Make modifications on fields
	public function showChildsOnFields($fields)
	{
		return collect($fields)->map(function($item) {
			if(get_class($item)=='Tiuswebs\ConstructorCore\Inputs\BelongsTo' && $item->show_id) {
				$input = $item->getProcessedInput($this);
				return [
					$input,
					Text::make($item->title_nt.' Id', $item->column.'_id')
				];
			} else if(get_class($item)=='Tiuswebs\ConstructorCore\Inputs\BelongsTo' && !$item->show_id) {
				return $item->getProcessedInput($this);
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
				return $item->setValue($this->data->$column)->setComponent($this);
			} else if (!is_array($column) && isset($this->data) && array_key_exists($column, collect($this->data)->toArray())) {
				return $item->setValue(null)->setComponent($this);
			} else if (!is_array($column) && !isset($this->data->$column)) {
				return $item->setValue($item->default_value)->setComponent($this);
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
					} else if (isset($this->data) && array_key_exists($column, collect($this->data)->toArray())) {
						$item->setValue(null);
					} else {
						$item->setValue($item->default_value)->setComponent($this);
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
		$styles[] = $this->getStylesByInput('FontWeight', 'font-weight');
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
			$class = $item->getSelectors($this->id);
			if(isset($parent)) {
				$parent = str_replace('_', '-', $parent);
				$class = str_replace($name, $parent.'-class', $class);
			}
			return compact('name', 'value', 'attribute', 'class', 'parent');
		})->whereNotNull('value')->values();
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

    public function replaceResults($value)
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
        $keys = collect($return->first())->keys()->all();
        $main_field = collect(['front_title', 'title', 'name'])->filter(function($item) use ($keys) {
        	return in_array($item, $keys);
        })->first();
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
    		if(!isset($this->values->$column) && $item->show_id) {
    			$get = $column.'_id';
    		}
    		$result = null;
    		if(isset($this->values->$get) && $this->values->$get=='item') {
    			$result = $this->item;
    		} else if (isset($this->values->$get) && isset($this->belongs_to_data[$column])) {
    			$result = $this->belongs_to_data[$column]->firstWhere('id', $this->values->$get);	
    		}
    		$this->$column = $result;
    	});
	}

	public function updateViewRender($html)
	{
		// Get fonts
		$fonts = $this->getStylesByInput('FontFamily')->whereNotNull('value')->filter(function($item) {
			return strlen($item['name']) > 0;
		})->mapWithKeys(function($item) {
			$render = $this->useFont(str_replace('-', '_', $item['name']));
			if(!isset($render)) {
				return [0 => null];
			}
			$value = trim($render->render(), "\n");
			return [$item['parent'].'-class' => $value];
		})->whereNotNull();

		// Get tailwind Classes
		$values = $this->getStylesByInput('TailwindClass')->groupBy('parent')->mapWithKeys(function($item, $key) use ($fonts) {
			$key = $key.'-class';
			$classes = $item->pluck('value')->whereNotNull()->filter(function($item) {
				return strlen($item) > 0;
			});
			if(isset($fonts[$key])) {
				$classes[] = $fonts[$key];
			}
			return [$key => $classes->implode(' ')];
		});

		// If there arent tailwind classes it doesnt add font, so in case it happens add the font
		foreach ($fonts as $key => $font) {
			if(!isset($values[$key])) {
				$values[$key] = $font;
			}
		}

		// Get types
		$this->getTypesFields()->filter(function($item) {
			return $item->hideAutomatically();
		})->map(function($item) {
			return [
				'class' => $item->getCssClassName(),
				'main_field' => $item->getColumnName($item->main_field)
			];
		})->each(function($item) use (&$values) {
			$value = $this->hide($item['main_field']);
			if(isset($values[$item['class']])) {
				$values[$item['class']] .= ' '.$value;
			} else {
				$values[$item['class']] = $value;
			}
		});

		// Replace results
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

	public function getRequirements()
    {
    	return collect([]);
    }

    public function getCategories()
	{
		$category = null;
		if(isset($this->category)) {
			$category = ucwords($this->category);
		}
		return json_encode([$category]);
	}

	public function getType()
    {
        return 'Normal';
    }

	public function hide($field) 
	{
        $value = $this->values->$field ?? null;
        if(!is_bool($value) && strlen($value) == 0){
            return "hidden";
        }
    }

    public function checkLink($field) 
    {
        $value = $this->values->$field ?? null;
        if(strlen($value)<=1){
            return "cursor-default pointer-events-none inline-block";
        }
    }

    public function getComponentClass()
    {
    	return 'overflow-x-hidden w-full overflow-y-hidden';
    }
}
