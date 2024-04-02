<?php

namespace Tiuswebs\ConstructorCore\Traits;

trait InputRules
{
	private $rules;

	public function rules($rules)
	{
		$this->rules = $rules;
		return $this;
	}

	public function getRules()
	{
		$rules = $this->rules ?? [];
		$rules = is_string($rules) ? [$rules] : $rules;
		return $rules;
	}
}
