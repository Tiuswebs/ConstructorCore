<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\Select;
use Tiuswebs\ConstructorCore\Inputs\Number;
use Illuminate\Support\Str;

class Font
{
    public $title;
    public $slug;
    public $url;

	public function __construct($title = nulll)
    {
        $this->title = $title;
        $this->slug = Str::snake($title);
    }

    public static function make($title = null) 
    {
        return new static($title);    
    }

    public function url($url)
    {
        $this->url = $url;
        return $this;
    }
}