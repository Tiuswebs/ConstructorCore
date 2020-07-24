<?php

namespace Tiuswebs\ConstructorCore;

use WeblaborMX\FileModifier\Helper;
use Illuminate\Support\Str;

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
