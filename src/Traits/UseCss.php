<?php

namespace Tiuswebs\ConstructorCore\Traits;

use Illuminate\Support\Str;

trait UseCss
{
	public $css_load = 'automatic'; // automatic, normal, hover, both

	public function cssType($css_load)
    {
        $this->css_load = $css_load;
        return $this;
    }

    public function getSelectors($id)
    {
    	$name = $this->column;
    	$name = str_replace('_', '-', $name);
    	$normal_class = '#'.$id.' .'.$name.'';
    	$hover_class = '#'.$id.' .'.$name.':hover';
    	if($this->css_load == 'automatic' && Str::contains($name, 'hover')) {
			return $hover_class;
    	} else if($this->css_load == 'automatic' || $this->css_load == 'normal') {
			return $normal_class;
    	} else if($this->css_load == 'hover') {
			return $hover_class;
    	}
    	$hover_class = str_replace($name, 'hover\:'.$name, $hover_class);
		return $normal_class.', '.$hover_class;
    }
}
