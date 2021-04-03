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
			'black'     => __('Black'),
			'white'     => __('White'),
			'gray'      => __('Gray'),
			'blue-gray' => __('Blue Gray'),
			'cool-gray' => __('Cool Gray'),
			'true-gray' => __('True Gray'),
			'warm-gray' => __('Warm Gray'),
			'red'       => __('Red'),
			'orange'    => __('Orange'),
			'amber'     => __('Amber'),
			'yellow'    => __('Yellow'),
			'lime'      => __('Lime'),
			'green'     => __('Green'),
			'emerald'   => __('Emerald'),
			'teal'      => __('Teal'),
			'cyan'      => __('Cyan'),
			'light-blue'=> __('Light Blue'),
			'blue'      => __('Blue'),
			'indigo'    => __('Indigo'),
			'violet'    => __('Violet'),
			'purple'    => __('Purple'),
			'fuchsia'   => __('Fuchsia'),
			'pink'      => __('Pink'),
			'rose'      => __('Rose'),
		];
	}
}
