<?php

namespace Tiuswebs\ConstructorCore;

use Tiuswebs\ConstructorCore\Inputs\Select;
use Tiuswebs\ConstructorCore\Inputs\Number;
use Illuminate\Support\Facades\Http;

class Result extends Component
{
    public $category = 'result';

    public function load()
    {
        $relation = $this->values->show;
        $sort = $this->values->sort;
        if($relation=='contents' && !is_bool($this->contents)) {
            $this->elements = $this->contents->$sort()->paginate($this->values->limit);
        } else {
            $url = "http://app.tiuswebs.com/api/example_data/{$relation}";
            $this->elements = collect(Http::get($url)->json())->map(function($item) {
                return (object) $item;
            })->take($this->values->limit);
        }
    }

    public function baseFields()
    {
        return [
            Select::make('Show')->default($this->showDefault())->options($this->showOptions()),
            Select::make('Sort')->default('latest')->options(['latest' => __('Latest'), 'oldest' => __('Oldest')]),
            Number::make('Limit of results', 'limit')->default(10),
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