<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\BelongsTo;
use Tiuswebs\ConstructorCore\Inputs\Select;
use Tiuswebs\ConstructorCore\Inputs\Number;

class MultimediaItem extends Core
{
	public $default_limit = 10;
	public $show_limit = true;
    public $default_sort = 'latest';

    public function load()
    {
    	parent::load();
    	if(!isset($this->gallery)) {
    		return;
    	}
        $elements = $this->getFromApi('multimedia_items');
        if($this->values->sort=='latest') {
            $elements = $elements->sortByDesc('created_at');
        } else if($this->values->sort=='oldest') {
            $elements = $elements->sortBy('created_at');
        } else if($this->values->sort=='random') {
            $elements = $elements->random($this->values->limit);
        }
        if($this->values->limit!=0 || !isset($this->values->limit)) {
            $elements = $elements->take($this->values->limit);
        }
        $results = $elements->values();
        $this->gallery->items = $results;
    }

    public function baseFields()
    {
        return [
            BelongsTo::make('Multimedia', 'gallery')->setTitle('Gallery'),
            Select::make('Sort')->default($this->default_sort)->options(['latest' => __('Latest'), 'oldest' => __('Oldest'), 'inRandomOrder' => __('Random')]),
	        Number::make('Limit of results', 'limit')->default($this->default_limit)->show($this->show_limit),
        ];
    }
}