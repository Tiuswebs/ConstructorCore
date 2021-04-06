<?php

namespace Tiuswebs\ConstructorCore\Inputs;

use Illuminate\Support\Str;

class Items extends Trix
{
	public function load()
	{
		$this->default_value = '<ul><li>List 1</li><li>List 2</li><li>List 3</li></ul>';
	}
	
	public function formatValue()
	{
		$value = parent::formatValue();
		$dom = new \DOMDocument;
	    $dom->loadHTML( $value );
	    return collect($dom->getELementsByTagName('li'))->map(function($item){
	    	return $item->nodeValue;
	    });
	}
}
