<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Str;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\Number;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Instagram extends Core
{
	public $default_limit = 10;
	public $category = 'widgets';

	public function load()
	{
		$this->loadInstagramData();
		return parent::load();
	}

	public function baseFields()
    {
        return [
            Text::make('Instagram Slug')->default('limpiezadecolchonesmerida'),
            Number::make('Limit of results', 'limit')->default($this->default_limit),
        ];
    }

	public function loadInstagramData()
	{
		$url = "https://www.instagram.com/{$this->values->instagram_slug}/channel/?__a=1";
		$this->instagram = Cache::remember('loadInstagram:'.$url, now()->addHour(), function() use ($url) {
			$instagram = [
				'categories' => null,
				'account'    => null,
				'posts'      => null,
			];
			$json = Http::get($url);
			if($json->failed()) {
				return [];
			}

			$json = $json->json();
			if(!isset($json['graphql'])) {
				return [];
			}

			$instagram['categories'] = $json['seo_category_infos'];
			$json = $json['graphql']['user'];
			$instagram['account'] = [
				'id' => $json['id'],
				'biography' => $json['biography'],
				'external_url' => $json['external_url'],
				'full_name' => $json['full_name'],
				'profile_pic_url' => $json['profile_pic_url'],
				'profile_pic_url_hd' => $json['profile_pic_url_hd'],
				'followed_by' => $json['edge_followed_by']['count'],
				'following' => $json['edge_follow']['count'],
			];

			$instagram['posts'] = collect($json['edge_owner_to_timeline_media']['edges'])->map(function($item) {
				return $item['node'];
			})->take($this->values->limit)->map(function($item) {
				return [
					'id' => $item['id'],
					'url' => "https://www.instagram.com/p/{$item['shortcode']}/",
					'dimensions' => $item['dimensions'],
					'display_url' => $this->convertToBase64($item['display_url']),
					'caption' => $item['edge_media_to_caption']['edges'][0]['node']['text'],
					'accessibility_caption' => $item['accessibility_caption'],
					'comments' => $item['edge_media_to_comment']['count'],
					'likes' => $item['edge_liked_by']['count'],
					'previews' => $item['edge_media_preview_like']['count'],
					'location' => $item['location'],
					'thumbnail_src' => $this->convertToBase64($item['thumbnail_src']),
					'thumbnail_resources' => collect($item['thumbnail_resources'])->map(function($item) {
						$item['src'] = $this->convertToBase64($item['src']);
						return $item;
					}),
				];
			});
			$instagram = json_encode($instagram);
			return json_decode($instagram);
		});
	}

	private function convertToBase64($url)
	{
		return Cache::remember('base64:'.$url, now()->addWeek(), function() use ($url) {
			$return = base64_encode(file_get_contents($url));
			return "data:image/png;base64,".$return;
		});
	}
}
