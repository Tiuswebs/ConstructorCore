<?php

namespace Tiuswebs\ConstructorCore\Texts;

use Tiuswebs\ConstructorCore\Constructor;

class Heading extends Text
{
	public function form()
	{
		$heading = $this;
		return view('front::texts.heading', compact('heading'))->render();
	}
}
