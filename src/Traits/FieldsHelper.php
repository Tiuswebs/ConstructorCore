<?php

namespace Tiuswebs\ConstructorCore\Traits;

use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\Color;
use Tiuswebs\ConstructorCore\Inputs\Boolean;
use Tiuswebs\ConstructorCore\QueryFields;

trait FieldsHelper 
{
	public function getDefaults()
	{
		$default_values = collect([
			'background_color' => 'transparent',
			'background_image' => '',
			'background_classes' => 'bg-cover',
			'padding_top' => '',
			'padding_bottom' => '',
			'padding_tailwind' => 'py-24',
			'with_container' => true,
		]);
		$values = $this->default_values;
		return $default_values->merge($values);
	}

	// Get the original fields
	public function getFields($reload = false)
	{
		// Avoiding loading every time
		if(isset($this->return_fields) && !$reload) {
			$fields = $this->return_fields;
			return QueryFields::make($this, $fields);
		}

		// Code
		$default_values = $this->getDefaults();
		$initial_fields = [];
		if($this->is_normal_component) {
			$initial_fields[] = Text::make('Component Name')->default($this->name);
			$initial_fields[] = Text::make('Component Id')->default($this->id);	
		}
		if($this->have_background_color) {
			$initial_fields[] = Color::make('Background Color')->default($default_values['background_color']);
			$initial_fields[] = Text::make('Background Image')->default($default_values['background_image']);
			$initial_fields[] = Text::make('Background Classes')->default($default_values['background_classes']);
		}
		if($this->have_paddings) {
			$initial_fields[] = Text::make('Padding Top')->default($default_values['padding_top']);
			$initial_fields[] = Text::make('Padding Bottom')->default($default_values['padding_bottom']);
		}
		if($this->have_container) {
			$initial_fields[] = Boolean::make('With Container')->default($default_values['with_container']);
		}
		
		$fields = $this->fields();
		$fields = collect($initial_fields)->merge($this->baseFields())->merge($fields)->whereNotNull()->flatten(1)->map(function($item) {
			if(!isset($item->is_helper)) {
				return $item;
			}
			return $item->handle();
		})->flatten(1);

		// Return
		$this->return_fields = $fields;
		return QueryFields::make($this, $fields);
	}
}