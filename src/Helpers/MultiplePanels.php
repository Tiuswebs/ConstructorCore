<?php

namespace Tiuswebs\ConstructorCore\Helpers;

use Tiuswebs\ConstructorCore\Components\Panel;
use Tiuswebs\ConstructorCore\Inputs\Number;
use Illuminate\Support\Str;

class MultiplePanels extends Helper
{
	public $default = 1;

	public function __construct($tab_title, $panel_title, $panel_values, $source = null)
	{
		$this->tab_title      = $tab_title;
		$this->tab_title_pl   = Str::plural($tab_title);
		$this->panel_title    = $panel_title;
		$this->panel_title_pl = Str::plural($panel_title);
		$this->panel_values   = $panel_values;
		$this->id             = 'multiple_panels_'.Str::snake($this->tab_title);
		$this->load();
	}

	public function handle()
	{
		$data = [
			Number::make('Totals '.$this->tab_title_pl, $this->id)->default($this->default)
		];

		$columns = $this->values->{$this->id} ?? $this->default;
		for($i=0; $i<$columns; $i++) 
		{
			$name   = $this->tab_title.' '.$i.' '.$this->panel_title_pl;
			$id     = $this->tab_title.'.'.$i.'.'.$this->panel_title_pl;
			$data[] = Number::make($name, $id)->default($this->default);
			$data[] = Panel::make($this->tab_title.' '.$i, $this->panel_values)->repeat($id);
		}
        return $data;
	}

	public function defaultPanels($default)
	{
		$this->default = $default;
		return $this;
	}
}
