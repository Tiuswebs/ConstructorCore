<?php

namespace Tiuswebs\ConstructorCore\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
  	public function setUp(): void
  	{
    	parent::setUp();
  	}

	protected function getEnvironmentSetUp($app)
	{
	    // perform environment setup
	}
}