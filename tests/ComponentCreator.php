<?php

namespace Tiuswebs\ConstructorCore\Tests;

use Tiuswebs\ConstructorCore\Component;
use Tiuswebs\ConstructorCore\Helpers\MultiplePanels;
use Tiuswebs\ConstructorCore\Inputs\Text;

class ComponentCreator extends Component
{
	public $fields; 
    public $category = 'component';

    public function fields()
    {
        return $this->fields;
    }

    public function addAttribute($name, $value)
    {
    	$this->$name = $value;
    	return $this;
    }

    public function addFields($fields)
    {
    	$this->fields = $fields;
    	return $this;
    }
}
