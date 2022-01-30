<?php

namespace Tiuswebs\ConstructorCore\Helpers;

use Tiuswebs\ConstructorCore\InputCore;

class Helper extends InputCore
{
	public $is_helper = true;

	public function formHtml($use_front_view = false)
	{
		return $this->form();
	}

	public function showHtml($object)
	{
		return $this->form();
	}
}
