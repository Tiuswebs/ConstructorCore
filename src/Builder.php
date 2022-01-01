<?php

namespace Tiuswebs\ConstructorCore;

use Livewire\Livewire;

class Builder
{
	public $components = [];
	public $data = [];
	public $page = null;

	public function __construct($components)
	{
		$this->components = $components;
		collect($this->components)->each(function($item) {
			Livewire::component($item->livewire_name, get_class($item));
		});
	}

	public function components()
	{
		return $this->components;
	}

	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}
}
