<?php

namespace Tiuswebs\ConstructorCore;

class Component extends Core
{
	public function load()
	{
		if(!isset($this->category)) {
			abort(406, 'A category is needed');
		}
	}
}
