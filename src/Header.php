<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\Select;

class Header extends Footer
{
    public $category = 'header';

    public function baseFields()
    {
        return [
            Select::make('Position')->default('relative')->options([
            	'relative' => 'Relative',
            	'fixed' => 'Fixed',
            	'absolute' => 'Absolute'
            ]),
        ];
    }

    public function getComponentClass()
    {
    	$default = parent::getComponentClass();
    	if($this->values->position=='fixed') {
    		return 'fixed top-0 left-0 right-0';
    	} else if($this->values->position=='absolute') {
    		return 'absolute top-0 left-0 right-0';
    	}
    	return $default;
    }
}