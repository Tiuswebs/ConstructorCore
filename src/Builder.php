<?php

namespace Tiuswebs\ConstructorCore;

class Builder
{
	public $components = [];
	public $data = [];
	public $page = null;

	public function __construct($components)
	{
		$this->components = $components;
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
