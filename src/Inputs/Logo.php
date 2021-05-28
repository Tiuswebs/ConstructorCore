<?php

namespace Tiuswebs\ConstructorCore\Inputs;

class Logo extends Text
{
	public $help = 'Will use the default one if empty';

	public function formatValue()
	{
		$value = parent::formatValue();
		if(strlen($value)>0) {
            return $value;
        }
        return $this->component->team->photo_url;
	}
}
