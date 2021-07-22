<?php

namespace Tiuswebs\ConstructorCore\Traits;

trait InputSetters
{
	public $default_value = null;
	public $attributes = ['class' => 'form-control form-control-sm'];
	public $conditional;
	public $resource;
	public $display_using;
	public $link;
	public $class = '';

	public function setColumn($value)
	{
		$this->column = $value;
		return $this;
	}

	public function help($help)
	{
		$this->help = $help;
		return $this;
	}

	public function setResource($resource)
	{
		$this->resource = $resource;
		return $this;
	}

	public function withMeta($attributes)
	{
		$attributes = collect($this->attributes)->merge($attributes)->toArray();
		$this->attributes = $attributes;
		return $this;
	}

	public function default($value, $force = false)
	{
		if(!is_string($value) && is_callable($value)) {
			$value = $value();
		}
		$this->default_value = $value;
		return $this;
	}

    public function setOpacity($opacity = null)
    {
        list($r, $g, $b) = sscanf($this->default_value, "#%02x%02x%02x");

        $rgba = "rgba($r, $g, $b, $opacity)";

        $this->default_value = $rgba;

        return $this;
    }
}
