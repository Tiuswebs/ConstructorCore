<?php

namespace Tiuswebs\ConstructorCore\Helpers;

use Tiuswebs\ConstructorCore\Components\Panel;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\Number;
use Illuminate\Support\Str;

class MultiplePanels extends Helper
{
	public $default = 1;

	public function __construct($tab_title, $panel_title, $panel_value, $source = null)
	{
		$this->tab_title      = $tab_title;
		$this->tab_title_pl   = Str::plural($tab_title);
		$this->panel_title    = $panel_title;
		$this->panel_title_pl = Str::plural($panel_title);
		$this->panel_value    = $panel_value;
		$this->id             = 'multiple_panels_'.Str::snake($this->tab_title);
		$this->load();
	}

	public function handle()
	{
		$data = [
			Number::make($this->tab_title_pl, $this->id)->default($this->default)
		];
		for($i=0; $i<$this->values->{$this->id}; $i++) 
		{
			$name   = $this->tab_title.' '.$i.' '.$this->panel_title_pl;
			$id     = $this->tab_title.'.'.$i.'.'.$this->panel_title_pl;
			$data[] = Number::make($name, $id)->default($this->default);
			$data[] = Panel::make($this->tab_title.' '.$i, [
	            Text::make('Title')->default('Support'),
	            Text::make('Text')->default('910-784-8015'),
	            Text::make('Link')->default('tel:910-784-8015'),
	        ])->repeat($id);
		}
		dd($data);
        return $data;
	}

	public function default($default)
	{
		$this->default = $default;
		return $this;
	}
}
