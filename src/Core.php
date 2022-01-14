<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Str;
use Tiuswebs\ConstructorCore\Traits\FieldsHelper;
use Tiuswebs\ConstructorCore\Traits\ConsumesApi;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;
use Livewire\Component;

/**
 * @package Tiuswebs\ConstructorCore
 *
 * @copyright  Copyright (c) 2020 - 2022 Weblabor. All rights reserved.
 * @author Carlos Escobar <carlosescobar@weblabor.mx>
 */
class Core extends Component
{
	use FieldsHelper, ConsumesApi;

	public $have_background_color = true;
	public $have_paddings = true;
	public $have_container = false;
	public $have_overflow = true;
	protected $contents = false;
	protected $use_on_template = null;
	protected $belongs_to_data = [];
	protected $belongs_to_list = [];
	protected $default_values = [];
	protected $show_view = true;
	protected $is_normal_component = true;
	protected $name = '';
	public $livewire_name;
	public $data;
	protected $values;
	protected $constructor;
	protected $team;
	protected $core_id;
	protected $view;
	protected $item;

	public function __construct($constructor = null)
	{
		$this->core_id = 'section-' . rand();
		$this->name = str_replace('Tiuswebs\Modules\Elements\\', '', get_class($this));
		$this->name = str_replace('Tiuswebs\ModulesApproved\Elements\\', '', $this->name);
		$this->livewire_name = str_replace('\\', '-', $this->name);
		if (is_null($this->view)) {
			$name = explode('\\', $this->name);
			$name[0] = strtolower($name[0]);
			$name[1] = str_replace('_', '-', Str::snake($name[1]));
			$name = implode('.', $name);
			$this->view = 'modules::' . $name;
		}

		$this->constructor = $constructor;
		$this->loadTeam();
		$this->loadValues();
		$this->loadBelongsToData();
		$this->load();
		$this->loadValues(true); // Load values again in case we are passing attributes on load
		$this->loadItem();
		parent::__construct();
	}

	public function render()
	{
		$component = $this;
		$values = $this->values;
		$data = compact('component', 'values');
		if (isset($this->constructor) && is_object($this->constructor)) {
			$data = collect($data)->merge($this->constructor->data)->all();
		}
		if (!$this->show_view) {
			return view('constructor::empty');
		}
		return view($this->view, $data);
	}

	public function renderCore()
	{
		$value = view("constructor::livewire-call", ['name' => $this->livewire_name])->render();
		$value = $this->updateViewRender($value);
		$core = $this;
		$component = $this;
		return view('constructor::module-outer', compact('core', 'value', 'component'));
	}

	public function loadTeam()
	{
		$team = \Cache::remember('loadTeamInfo', now()->addDay(), function () {
			$teams = $this->getFromApi('teams')->filter(function ($item) {
				return Str::contains($item->photo_url, 'tiuswebs');
			});
			return $teams->random();
		});
		$this->team = $team;
	}

	public function loadItem()
	{
		if (is_null($this->use_on_template)) {
			return;
		}
		$template = explode('-', $this->use_on_template);
		if ($template[0] != 'single') {
			return;
		}
		$relation = $template[1];
		if ($relation == 'documentation books') {
			$relation = 'documentations';
		}
		$elements = $this->getFromApi($relation);
		$this->item = $elements->first();
	}

	public function load()
	{
		//
	}

	/**
	 * Inputs and Types to use on the views
	 *
	 * @return void
	 */
	public function fields()
	{
		return [];
	}

	public function baseFields()
	{
		return [];
	}

	public function getInlineStyles()
	{
		$styles = [];
		$styles[] = $this->getStylesByInput('TextColor', 'color');
		$styles[] = $this->getStylesByInput('BackgroundColor', 'background-color');
		$styles[] = $this->getStylesByInput('BorderColor', 'border-color');
		$styles[] = $this->getStylesByInput('FontWeight', 'font-weight');
		$styles[] = $this->getStylesByInput('BackgroundImage', 'background-image', 'url({value})');
		$styles = collect($styles)->flatten(1)->groupBy('class');
		return $styles;
	}

	public function getStylesByInput($name, $attribute = null, $format_value = '{value}')
	{
		return $this->getFields()->expandPanels()->expandTypes()->get()->filter(function ($item) use ($name) {
			return get_class($item) == 'Tiuswebs\ConstructorCore\Inputs\\' . $name;
		})->map(function ($item) use ($attribute, $format_value) {
			$name = $item->column;
			$value = $this->values->$name ?? null;
			if (strlen($value) == 0) {
				$value = null;
			}
			$type = $item->parent;
			$parent = $type->column ?? null;
			$name = str_replace('_', '-', $name);
			$class = $item->getSelectors($this->core_id);
			if (isset($parent)) {
				$parent = str_replace('_', '-', $parent);
				$class = str_replace($name, $parent . '-class', $class);
			}
			if (!is_null($value)) {
				$value = str_replace('{value}', $value, $format_value);
			}
			return compact('name', 'value', 'attribute', 'class', 'parent', 'type');
		})->whereNotNull('value')->values();
	}

	public function loadValues($reload = false)
	{
		// Get normal values
		$values = $this->getFields($reload)->withoutPanels()->withoutTypes()->getValues();
		$this->values = (object) $values;

		// Get normal values from types and panels
		$new_values = $this->getFields($reload)->typesAndPanels()->expandPanels()->expandTypes()->getValues();
		$values = $values->merge($new_values);
		$this->values = (object) $values;

		// Get types except from panels with repeat
		$new_values = $this->getFields($reload)->expandPanelsWithRepeat(false)->onlyTypes()->getValues()->whereNotNull();
		$values = $values->merge($new_values);

		// Get types from panels with repeat
		$new_values = $this->getFields($reload)->onlyPanels()->expandPanelsWithRepeat()->onlyTypes()->getValues()->whereNotNull();
		$values = $values->mergeCombine($new_values);

		// Save all
		$values = $values->all();

		// Avoid user to put a component id starting with a number
		if (isset($values['component_id']) && strlen($values['component_id']) > 0 && ctype_digit(substr($values['component_id'], 0, 1))) {
			$this->core_id = 'section-' . $values['component_id'];
		} else if (isset($values['component_id'])) {
			$this->core_id = $values['component_id'];
		}
		if (isset($values['component_name'])) {
			$this->name = $values['component_name'];
		}
		$values = json_encode($values);
		$this->values = json_decode($values);
	}

	public function getPaddingStyle()
	{
		$styles = ['padding_top', 'padding_bottom'];
		return collect($this->values)->filter(function ($item, $key) use ($styles) {
			return in_array($key, $styles) && isset($item) && strlen($item) > 0;
		})->map(function ($item, $key) {
			$key = str_replace('_', '-', $key);
			return $key . ': ' . $item . ' !important';
		})->implode('; ');
	}

	public function useFont($font_column)
	{
		$font = (new FontFamily)->getFonts()->firstWhere('slug', $this->values->$font_column);
		if (!isset($font) || $font->slug == 'inherit') {
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
		if (isset($this->belongs_to_list[$column])) {
			return $this->belongs_to_list[$column];
		}

		$relation = Str::plural(str_replace('_', ' ', Str::snake($model)));
		$elements = $this->getFromApi($relation);
		$return = $elements->random($elements->count() > 10 ? 10 : $elements->count());
		$keys = collect($return->first())->keys()->all();
		$main_field = collect(['front_title', 'title', 'name'])->filter(function ($item) use ($keys) {
			return in_array($item, $keys);
		})->first();
		$this->belongs_to_data[$column] = $return;
		$return = $return->pluck($main_field, 'id');
		$this->belongs_to_list[$column] = $return;
		return $return;
	}

	public function loadBelongsToData()
	{
		$this->getFields()->expandPanels()->expandTypes()->get()->filter(function ($item) {
			return get_class($item) == 'Tiuswebs\ConstructorCore\Inputs\BelongsTo';
		})->each(function ($item) {
			$column = $item->column;
			$get = $column;
			if (!isset($this->values->$column) && $item->show_id) {
				$get = $column . '_id';
			}
			$result = null;
			if (isset($this->values->$get) && $this->values->$get == 'item' && isset($this->item) && $this->item) {
				$result = $this->item;
			} else if (isset($this->values->$get) && isset($this->belongs_to_data[$column]) && $this->values->$get != 'item') {
				$result = $this->belongs_to_data[$column]->firstWhere('id', $this->values->$get);
			} else if (isset($this->belongs_to_data[$column]) && $this->belongs_to_data[$column]->count() > 0) {
				$options = collect($item->options)->keys();
				if ($options->count() == 0) {
					$result = null;
				} else {
					$default_option = $options->random();
					$result = $this->belongs_to_data[$column]->firstWhere('id', $default_option);
				}
			} else if (isset($this->belongs_to_data[$column])) {
				$this->show_view = false;
			}

			// Dont load component if a belongsTo doesnt have a value
			if (is_null($result)) {
				$this->show_view = false;
			}
			$this->$column = $result;
		});
	}

	public function updateViewRender($html)
	{
		$html = $this->addStylesAndClasses($html);
		$html = $this->addPopupOnLinks($html);
		$html = $this->replaceHeadingTags($html);
		return $html;
	}

	/**
	 * Replace all heading tags from admin values
	 *
	 * @param string $html
	 * @return string
	 */
	public function replaceHeadingTags($html)
	{
		$values = $this->getFields()->expandPanels()->onlyTypesCalled('Title')->expandTypes()->getValues();
		$values = $values->filter(function ($item, $key) {
			return strpos($key, '_heading') !== false;
		})->values()->all();

		preg_match_all('/<h[1-6].*?>[\S\s]*?<\/h[1-6]>/', $html, $matches);
		$matches = $matches[0];

		collect($matches)->each(function ($item, $key) use ($values, &$html) {
			if (!isset($values[$key])) {
				return;
			}
			$value = preg_replace('/<h[1-6](.*?)>([\S\s]*?)<\/h[1-6]>/', "<{$values[$key]}$1>$2</{$values[$key]}>", $item);
			$html = str_replace($item, $value, $html);
		});

		return $html;
	}

	public function addStylesAndClasses($html)
	{
		// Get fonts
		$fonts = $this->getStylesByInput('FontFamily')->whereNotNull('value')->filter(function ($item) {
			return strlen($item['name']) > 0;
		})->mapWithKeys(function ($item) {
			$render = $this->useFont(str_replace('-', '_', $item['name']));
			if (!isset($render)) {
				return [0 => null];
			}
			$value = trim($render->render(), "\n");
			return [$item['parent'] . '-class' => $value];
		})->whereNotNull();

		// Get tailwind Classes
		$tailwind_classes = $this->getStylesByInput('TailwindClass');
		$values = collect([]);

		// if doesnt have parent so replace the name with the value
		$tailwind_classes->whereNull('parent')->each(function ($item) use (&$values) {
			if (isset($values[$item['name']])) {
				$values[$item['name']] .= ' ' . $item['value'];
			} else {
				$values[$item['name']] = $item['value'];
			}
		});

		// If has parent is for types
		$tailwind_classes->whereNotNull('parent')->groupBy('parent')->each(function ($item, $key) use ($fonts, &$values) {
			$type = $item->first()['type']->default_classes;
			$key = $key . '-class';
			$classes = $item->pluck('value')->prepend($type)->whereNotNull()->filter(function ($item) {
				return strlen($item) > 0;
			});
			if (isset($fonts[$key])) {
				$classes[] = $fonts[$key];
			}
			$values[$key] = $classes->implode(' ');
		});

		// If there arent tailwind classes it doesnt add font, so in case it happens add the font
		foreach ($fonts as $key => $font) {
			if (!isset($values[$key])) {
				$values[$key] = $font;
			}
		}

		// Add border to types with BorderColor if on class there aren't any border-* class
		$border_colors = $this->getStylesByInput('BorderColor')->groupBy('parent')->keys()->values();
		if ($border_colors->count() > 0) {
			$border_colors->map(function ($item) {
				return $item . '-class';
			})->filter(function ($item) use ($values) {
				return isset($values[$item]) && !Str::contains($values[$item], 'border');
			})->each(function ($item) use (&$values) {
				$values[$item] .= ' border';
			});
		}

		// Get types
		$this->getFields()->expandPanels()->onlyTypes()->get()->filter(function ($item) {
			return $item->hideAutomatically();
		})->map(function ($item) {
			return [
				'class' => $item->getCssClassName(),
				'main_field' => $item->getColumnName($item->main_field)
			];
		})->each(function ($item) use (&$values) {
			$value = $this->hide($item['main_field']);
			if (isset($values[$item['class']])) {
				$values[$item['class']] .= ' ' . $value;
			} else {
				$values[$item['class']] = $value;
			}
		});

		// Replace results
		foreach ($values as $key => $value) {
			$before = ' ';
			$html = str_replace($before . $key, $before . $key . ' ' . $value, $html);
			$before = '"';
			$html = str_replace($before . $key, $before . $key . ' ' . $value, $html);
			$before = "'";
			$html = str_replace($before . $key, $before . $key . ' ' . $value, $html);
		}
		return $html;
	}

	public function addPopupOnLinks($html)
	{
		$links = [
			'https://youtube.com',
			'https://www.youtube.com',
			'http://youtube.com',
			'http://www.youtube.com',
			'https://youtu.be',
			'https://www.youtu.be',
			'http://youtu.be',
			'http://www.youtu.be',
		];
		foreach ($links as $link) {
			$html = str_replace('href="' . $link, 'data-type="popup" data-popup-type="iframe" href="' . $link, $html);
			if (Str::contains($link, 'youtu.be')) {
				$html = str_replace($link . '/', 'https://youtube.com/watch?v=', $html);
			}
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
		if (isset($this->category)) {
			$category = ucwords($this->category);
		}
		return json_encode([$category]);
	}

	public function getType()
	{
		return 'Normal';
	}

	/**
	 * Returns the 'hidden' Tailwind class if the value is false. Otherwise returns the default value
	 *
	 * @param mixed $show
	 * @param mixed $default
	 * @return mixed
	 */
	public function show($show, $default = null)
	{
		if (!$show) {
			return "hidden";
		}
		return $default;
	}

	/**
	 * Returns the 'hidden' Tailwind class if the value given is empty
	 *
	 * @param mixed $field
	 * @return string
	 */
	public function hide($field)
	{
		$value = property_exists($this->values, $field) ? $this->values->$field : $field;
		if (!is_bool($value) && strlen($value) == 0) {
			return "hidden";
		}
	}

	/**
	 * Deactivates a link if the given value is empty or '#'
	 *
	 * @param mixed $field
	 * @return void
	 */
	public function checkLink($field)
	{
		$value = property_exists($this->values, $field) ? $this->values->$field : $field;
		if ((empty($value)) || (is_string($value) && strlen($value) <= 1) || (is_bool($field) && !$field)) {
			return "cursor-default pointer-events-none";
		}
	}

	public function getComponentClass()
	{
		if (!$this->have_overflow) {
			return;
		}
		return 'overflow-x-hidden w-full overflow-y-hidden';
	}

	public function getValues()
	{
		return $this->values;
	}
}
