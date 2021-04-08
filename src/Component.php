<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;

class Component extends Core
{
	public function load()
	{
		if(!isset($this->category)) {
			abort(406, 'A category is needed');
		}
	}
}
