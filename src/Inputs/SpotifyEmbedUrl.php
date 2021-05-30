<?php

namespace Tiuswebs\ConstructorCore\Inputs;

class SpotifyEmbedUrl extends Text
{
	public function formatValue()
	{
		$value = parent::formatValue();
		$value = explode('?', $value)[0];
		$value = explode('spotify.com/', $value)[1] ?? null;
		if(is_null($value)) {
			return;
		}
		return "https://open.spotify.com/embed/{$value}";
	}
}
