<?php

namespace Tiuswebs\ConstructorCore\Inputs;

use Illuminate\Support\Str;

class BelongsTo extends Select
{
	public $show_id = true;
	public $filter_query = null;

	public function setComponent($component)
	{
		$options = $component->getBelongsToOptions($this->original_title, $this->column);
		if(isset($this->filter_query)) {
			$function = $this->filter_query;
			$options = $function($options);
		}
		return $this->options($options->all())->default($options->keys()->random($options->count())->first());
	}

	public function setShowId($value)
	{
		$this->show_id = $value;
		return $this;
	}

	public function filterQuery($function)
	{
		$this->filter_query = $function;
		return $this;
	}
}
