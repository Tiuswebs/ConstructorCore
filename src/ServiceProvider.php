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

        Collection::macro('toArrayAllInside', function () {
            return $this->map(function ($value) {
                if (is_object($value)) {
                    return (array) $value;
                } else if (is_array($value)) {
                    return collect($value)->toArrayAll();
                }
                return $value;
            });
        });

        Collection::macro('toArrayAll', function () {
            return $this->toArrayAllInside()->toArrayAllInside()->all();
        });

        Collection::macro('mergeCombine', function ($array2) {
            $result = [];
            $array = $this->all();
            $array2 = $array2->all();
            foreach($array as $key => $item) {
                if(is_array($item)) {
                    $result[$key] = [];
                    foreach($item as $key2 => $item2) {
                        if(is_array($item2)) {
                            foreach($item2 as $key3 => $item3) {
                                $result[$key][$key2][$key3] = $item3;
                            }
                        } else {
                            $result[$key][$key2] = $item2;
                        }
                    }
                } else {
                    $result[$key] = $item;
                }
            }
            foreach($array2 as $key => $item) {
                if(is_array($item)) {
                    foreach($item as $key2 => $item2) {
                        if(is_array($item2)) {
                            foreach($item2 as $key3 => $item3) {
                                $result[$key][$key2][$key3] = $item3;
                            }
                        } else {
                            $result[$key][$key2] = $item2;
                        }
                    }
                } else {
                    $result[$key] = $item;
                }
            }
            return collect($result);
        });
    }
}
