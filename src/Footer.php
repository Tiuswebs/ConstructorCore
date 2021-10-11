<?php

namespace Tiuswebs\ConstructorCore;

class Footer extends Core
{
    public $category = 'footer';
    public $total_columns = 1;
    public $cruds = ['menus'];

    public function load()
    {
        parent::load();
        $this->loadColumns();
    }

    private function loadColumns()
    {
        $data = collect([]);

        if(in_array('menus', $this->cruds)) {
            // load menus
            $menus = $this->getFromApi('menu')->filter(function($item) {
                return count($item->elements) <= 5 && count($item->elements) >= 2;
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
            $offices = $this->getFromApi('office')->take(5)->map(function($item) {
            	$item->type = 'Office';
            	return $item;
            });
            $data = $data->merge($offices);
        }

        if($this->total_columns>1) {
            $this->columns = $data->random($this->total_columns);    
        } else if($this->total_columns==1) {
            $this->menu = $data->random();
        }
        
    }

    public function getColumnClasses()
    {
        $columns = $this->total_columns;
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