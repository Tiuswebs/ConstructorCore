<?php

namespace Tiuswebs\ConstructorCore;

use WeblaborMX\FileModifier\Helper;
use Illuminate\Support\Str;

class GetBuilder
{
	public static function from($namespace)
	{
		$directory = str_replace('Tiuswebs\\', '', $namespace);
		$directory = str_replace('\\', '/', $directory);
		$directory = str_replace('ModulesApproved/', 'modules_approved/', $directory);
		$directory = str_replace('Modules/', 'modules/', $directory);
		$directory = str_replace('/Elements', '/src/elements', $directory);
		$folder = Helper::folder(base_path($directory));
		$namespaces = collect($folder->files())->filter(function($item) {
			return !Str::contains($item, ['.DS_Store', '.json']);
		})->map(function($item) use ($namespace) {
			$file = str_replace('/', '\\', $item);
			$file = str_replace('.php', '', $file);
			return $namespace.'\\'.$file;
		})->map(function($item) {
			try {
				$object = new $item();
			} catch (\Exception $e) {
				abort(406, 'Error on '.$item.': '.$e->getMessage());
			}
			return $object;
		});
		return new Builder($namespaces->all());
	}
}
