<?php

namespace Tiuswebs\ConstructorCore\Texts;

class Information extends Text
{
	public function getValue($object)
	{
		$column = $this->column;
		if(!is_object($object->$column)) {
			return;
		}
		$extra = $this->extra;
		return $object->$column->$extra;
	}

	public function form()
	{
		return '<div class="alert info"><i class="fa fa-info-circle"></i> <b>'.$this->title.':</b> '.$this->text.'</div>';
	}
}
