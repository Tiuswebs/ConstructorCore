<?php

namespace Tiuswebs\ConstructorCore\Inputs;

class BelongsTo extends Select
{
	public $show_id = true;
	public $filter_query = null;

	public function getProcessedInput($component)
	{
		return \Cache::store('array')->remember('getProcessedInput:'.$this->column, now()->addHour(), function() use ($component) {
			$options = $component->getBelongsToOptions($this->original_title, $this->column);
			if(isset($this->filter_query)) {
				$function = $this->filter_query;
				$options = $function($options);
			}
			return $this->options($options->all())->default('item');
		});
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
