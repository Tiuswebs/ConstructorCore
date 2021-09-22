<?php

namespace Tiuswebs\ConstructorCore\Traits;

use Illuminate\Support\Facades\Http;

trait ConsumesApi 
{
	public function getFromApi($type)
	{
    	$url = config('app.tiuswebs_api');
		$url = "{$url}/api/example_data/{$type}";
		return collect(json_decode(Http::get($url)->body()))->recursive();
	}

	public function dataFromApi($type)
	{
    	$url = config('app.tiuswebs_api');
		$url = "{$url}/api/{$type}";
		return collect(json_decode(Http::get($url)->body()))->recursive();
	}
}