<?php

namespace Tiuswebs\ConstructorCore\Inputs;

class Logo extends Text
{
	public $help = 'Will use the default one if empty';

	public function formatValue()
	{
		$value = parent::formatValue();
		if(!is_null($value) && strlen($value)>0) {
            return $value;
        }
        if(!is_object($this->component->team)) {
        	return;
        }
        return $this->component->team->photo_url;
	}
}
