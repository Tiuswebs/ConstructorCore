<?php

namespace Tiuswebs\ConstructorCore\Inputs;

use Tiuswebs\ConstructorCore\Font;

class BasicColor extends Select
{
	public function load()
	{
		$this->options($this->loadOptions());
	}

	public function loadOptions()
	{
		return [
			'gray'      => __('Gray'),
			'blue-gray' => __('Blue Gray'),
			'cool-gray' => __('Cool Gray'),
			'true-gray' => __('True Gray'),
			'warm-gray' => __('Warm Gray'),
			'red'       => __('Red'),
			'orange'    => __('orange'),
			'amber'     => __('amber'),
			'yellow'    => __('Yellow'),
			'lime'      => __('lime'),
			'green'     => __('Green'),
			'emerald'   => __('emerald'),
			'teal'      => __('teal'),
			'cyan'      => __('cyan'),
			'light-blue'=> __('Light Blue'),
			'blue'      => __('Blue'),
			'indigo'    => __('Indigo'),
			'violet'    => __('violet'),
			'purple'    => __('Purple'),
			'fuchsia'   => __('fuchsia'),
			'pink'      => __('Pink'),
			'rose'      => __('rose'),
		];
	}
}
