<?php

namespace Tiuswebs\ConstructorCore\Components;

use Tiuswebs\ConstructorCore\Inputs\Input;
use Tiuswebs\ConstructorCore\Traits\WithWidth;

class Component extends Input
{
	use WithWidth;

	public $is_input = false;

	public function formHtml($use_front_view = false)
	{
		return $this->form();
	}

	public function showHtml($object)
	{
		return $this->form();
	}
}
