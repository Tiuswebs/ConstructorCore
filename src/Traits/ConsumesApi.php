<?php

namespace Tiuswebs\ConstructorCore\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

trait ConsumesApi 
{
	public function getFromApi($type)
	{
		return Cache::remember('getFromApi:'.$type, now()->addDay(), function() use ($type) {
			$url = config('app.tiuswebs_api');
			$url = "{$url}/api/example_data/{$type}";
			return collect(json_decode(Http::get($url)->body()))->recursive();
		});
	}

	public function dataFromApi($type)
	{
		return Cache::remember('dataFromApi:'.$type, now()->addDay(), function() use ($type) {
	    	$url = config('app.tiuswebs_api');
			$url = "{$url}/api/{$type}";
			return collect(json_decode(Http::get($url)->body()))->recursive();
		});
	}
}