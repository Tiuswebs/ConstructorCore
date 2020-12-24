<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\Select;
use Tiuswebs\ConstructorCore\Inputs\Number;
use Illuminate\Support\Facades\Http;

class Result extends Component
{
    public $category = 'result';
    public $default_limit = 10;
    public $default_sort = 'latest';

    public function load()
    {
        $relation = $this->values->show;
        $sort = $this->values->sort;
        if($relation=='contents' && !is_bool($this->contents)) {
            $this->elements = $this->contents->$sort()->paginate($this->values->limit);
        } else {
            $url = "http://app.tiuswebs.com/api/example_data/{$relation}";
            $elements = collect(Http::get($url)->json())->map(function($item) {
                return (object) $item;
            });
            if($this->values->sort=='latest') {
                $elements = $elements->sortByDesc('created_at');
            } else if($this->values->sort=='oldest') {
                $elements = $elements->sortBy('created_at');
            } else if($this->values->sort=='random') {
                $elements = $elements->random($this->values->limit);
            }
            $this->elements = $elements->take($this->values->limit);
        }
    }

    public function baseFields()
    {
        return [
            Select::make('Show')->default($this->showDefault())->options($this->showOptions()),
            Select::make('Sort')->default($this->default_sort)->options(['latest' => __('Latest'), 'oldest' => __('Oldest'), 'random' => __('Random')]),
            Number::make('Limit of results', 'limit')->default($this->default_limit),
        ];
            
    }

    private function showOptions()
    {
        $options = [
            'partners' => __('Partners'), 
            'promotions' => __('Promotions'), 
            'products' => __('Products'),
            'banners' => __('Banners'), 
        ];
        if($this->contents) {
            $options['contents'] = __('Content');
        }
        return $options;
    }

    private function showDefault()
    {
        if(isset($this->default_result)) {
            return $this->default_result;
        }
        if($this->contents) {
            return 'contents';
        }
        return 'products';
    }
}