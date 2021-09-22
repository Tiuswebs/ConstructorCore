<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Support\Collection;
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
            $var = '$__env->{"__pushonce_" . md5(__FILE__ . ":" . __LINE__)}';
            return "<?php if(!isset({$var})): {$var} = true; \$__env->startPush({$expression}); ?>";
        });

        Blade::directive('endpushonce', function ($expression) {
            return '<?php $__env->stopPush(); endif; ?>';
        });
        
        Blade::directive('pushoncebykey', function ($expression) {
            $var = "\$__env->{'__pushoncebykey_' . explode(':', {$expression})[1]}";
            return "<?php if(!isset({$var})): {$var} = true; \$__env->startPush(explode(':', {$expression})[0]); ?>";
        });

        Blade::directive('endpushoncebykey', function ($expression) {
            return '<?php $__env->stopPush(); endif; ?>';
        });

        Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value)) {
                    return collect($value)->recursive();
                } else if (is_object($value)) {
                    foreach($value as &$item) {
                        if (is_array($item)) {
                            $item = collect($item)->recursive();
                        }
                    }
                }
                return $value;
            });
        });
    }
}
