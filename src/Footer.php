<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Facades\Http;

class Footer extends Core
{
    public $category = 'footer';
    public $columns = 4;

    public function load()
    {
        parent::load();
        $this->loadColumns();
    }

    private function loadColumns()
    {
    	$url = config('app.tiuswebs_api');

    	// load menus
        $new_url = "{$url}/api/example_data/menu";
        $menus = collect(json_decode(Http::get($new_url)->body()))->take(5)->map(function($item) {
        	$item->type = 'Menu';
        	return $item;
        });

        // load offices
        $new_url = "{$url}/api/example_data/office";
        $offices = collect(json_decode(Http::get($new_url)->body()))->take(5)->map(function($item) {
        	$item->type = 'Office';
        	return $item;
        });

        $elements = $menus->merge($offices);
        $this->columns = $elements->random($this->columns);
    }

    public function getColumnClasses()
    {
        $columns = count($this->columns);
        switch ($columns) {
            case 1:
                return 'col-12 col-md-8 col-lg-8';
            case 2:
                return 'col-6 col-md-4 col-lg-4';
            case 3:
                return 'col-6 col-md-4 col-lg-2';
            case 4:
                return 'col-6 col-md-4 col-lg-2';
        }
    }
}