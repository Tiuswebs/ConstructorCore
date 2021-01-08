<?php

namespace Tiuswebs\ConstructorCore\Inputs;

use Illuminate\Support\Str;

class BelongsTo extends Select
{
	public function setComponent($component)
	{
		$options = $component->getBelongsToOptions($this->original_title, $this->column);
		return $this->options($options->all())->default($options->keys()->random($options->count())->first());
	}
}
