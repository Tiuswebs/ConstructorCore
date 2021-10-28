<?php

namespace Tiuswebs\ConstructorCore\Types;

use Tiuswebs\ConstructorCore\Inputs\TailwindClass;
use Tiuswebs\ConstructorCore\Inputs\TextColor;
use Tiuswebs\ConstructorCore\Inputs\Text;
use Tiuswebs\ConstructorCore\Inputs\FontFamily;
use Tiuswebs\ConstructorCore\Inputs\FontWeight;
use Tiuswebs\ConstructorCore\Inputs\Select;

class Title extends Type
{
	/**
	 * Get all the heading options
	 * 
	 * @return array
	 */
	private function getHeadings()
	{
		return [
			'h1' => 'h1',
			'h2' => 'h2',
			'h3' => 'h3',
			'h4' => 'h4',
			'h5' => 'h5',
			'h6' => 'h6',
		];
	}

	/**
	 * Fields for the title component
	 *
	 * @return array
	 */
	public function fields()
	{
		return [
			Text::make("{$this->original_title} Text")->default('Title'),
			TextColor::make("{$this->original_title} Color")->default('#111827'),
			TailwindClass::make("{$this->original_title} Size")->default('text-3xl'),
			FontFamily::make("{$this->original_title} Font"),
			FontWeight::make("{$this->original_title} Weight"),
			TailwindClass::make("{$this->original_title} Classes")->default(''),
			Select::make("{$this->original_title} Heading")
				->options($this->getHeadings())
				->default('h3'),
		];
	}
}
