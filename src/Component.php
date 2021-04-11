<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Str;

class Component extends Core
{
	public function load()
	{
		if(isset($this->category)) {
			return;
		}
		$contains_belongs_to = $this->getAllFields()->filter(function($item) {
			return Str::contains(get_class($item), 'BelongsTo');
		})->count() > 0;
		if($contains_belongs_to) {
			return;
		}
		abort(406, 'A category is needed');
	}
}
