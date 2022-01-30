<?php

namespace Tiuswebs\ConstructorCore\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tiuswebs\ConstructorCore\Tests\ComponentCreator;
use Tiuswebs\ConstructorCore\ServiceProvider;

class TestCase extends Orchestra
{
  	public function setUp(): void
  	{
    	parent::setUp();
  	}

  	protected function getPackageProviders($app)
  	{
	    return [
	      	ServiceProvider::class,
	    ];
  	}

	protected function getEnvironmentSetUp($app)
	{
	    $app['config']->set('app.tiuswebs_api', 'http://app.tiuswebs.com');
	}

	public function createComponent($fields)
	{
		$component = (new ComponentCreator)->addFields($fields)->loadAll();
		return $component->values;
	}
}