<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\ServiceProvider as Provider;
use WeblaborMX\FileModifier\Helper;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Livewire;
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

        $directories = ['modules', 'modules_approved'];

        collect($directories)->each(function($dir) {
            $directory = $dir.'/src/Elements';
            $folder = Helper::folder(base_path($directory));
            if(!$folder->exists()) {
                return;
            }
            collect($folder->files())->filter(function($item) {
                return Str::endsWith($item, '.php');
            })->each(function($item) use ($dir) {
                $item = str_replace('.php', '', $item);
                $class = 'Tiuswebs/'.ucfirst(Str::camel($dir)).'/Elements/'.$item;
                $class = str_replace('/', '\\', $class);
                $livewire_name = str_replace('/', '-', $item);
                Livewire::component($livewire_name, $class);
            });
        });

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

        Collection::macro('mergeCombine', function ($array2) {
            $result = [];
            $array = $this->all();
            $array2 = $array2->all();
            foreach($array as $key => $item) {
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
