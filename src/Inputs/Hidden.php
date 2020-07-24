<?php

namespace Tiuswebs\ConstructorCore\Inputs;

class Hidden extends Input
{
	public $show_on_index = false;
	public $show_on_show = false;
	public $needs_to_be_on_panel = false;

	public function form()
	{
		return \Form::hidden($this->column, $this->default_value, $this->attributes);
	}

	public function formHtml()
	{
		return $this->form();
	}
}
