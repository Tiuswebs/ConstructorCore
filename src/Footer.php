<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Facades\Http;

class Footer extends Core
{
    public $category = 'footer';
    public $columns = 1;
    public $cruds = ['menus'];

    public function load()
    {
        parent::load();
        $this->loadColumns();
    }

    private function loadColumns()
    {
    	$url = config('app.tiuswebs_api');
        $data = collect([]);

        if(in_array('menus', $this->cruds)) {
            // load menus
            $new_url = "{$url}/api/example_data/menu";
            $menus = collect(json_decode(Http::get($new_url)->body()))->filter(function($item) {
                return count($item->elements) <= 5 && count($item->elements) >= 3;
            })->map(function($item) {
                $item->elements = collect($item->elements)->whereNotNull('title');
                return $item;
            })->take(5)->map(function($item) {
                $item->type = 'Menu';
                return $item;
            });
            $data = $data->merge($menus);
        }
            
        if(in_array('offices', $this->cruds)) {
            // load offices
            $new_url = "{$url}/api/example_data/office";
            $offices = collect(json_decode(Http::get($new_url)->body()))->take(5)->map(function($item) {
            	$item->type = 'Office';
            	return $item;
            });
            $data = $data->merge($offices);
        }

        if($this->columns>1) {
            $this->columns = $data->random($this->columns);    
        } else if($this->columns==1) {
            $this->menu = $data->random();
        }
        
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