<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\ServiceProvider as Provider;
use Blade;

class ServiceProvider extends Provider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'constructor');

        Blade::directive('pushonce', function ($expression) {
		    $isDisplayed = '__pushonce_'.trim(substr($expression, 2, -2));
		    return "<?php if(!isset(\$__env->{$isDisplayed})): \$__env->{$isDisplayed} = true; \$__env->startPush{$expression}; ?>";
		});
		Blade::directive('endpushonce', function ($expression) {
		    return '<?php $__env->stopPush(); endif; ?>';
		});
    }
}
