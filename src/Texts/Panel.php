<?php

namespace Tiuswebs\ConstructorCore\Texts;

use Tiuswebs\ConstructorCore\Constructor;

class Panel extends Text
{
	public function formHtml()
	{
		$html = Constructor::getInputFormHtml();
		$html = str_replace('{title}', $this->title, $html);
		$html = str_replace('{value}', $this->form(), $html);
		return $this->form_before.$html.$this->form_after;
	}

	public function showHtml($object)
	{
		$html = Constructor::getInputShowHtml();
		$html = str_replace('{title}', $this->title, $html);
		$html = str_replace('{value}', $this->getValueProcessed($object), $html);
		return $html;
	}

	public function getValue($object)
	{
		return $this->fields()->map(function($item) use ($object) {
			return $item->showHtml($object);
		})->implode('');
	}

	public function form()
	{
		return $this->fields()->map(function($item) {
			return $item->formHtml();
		})->implode('');
	}

	private function filterFields($where)
	{
		return collect($this->column)->filter(function($item) {
			return isset($item);
		})->flatten()->filter(function($item) use ($where) {
			$field = 'show_on_'.$where;
			return $item->$field;
		});
	}

	public function fields()
	{
		return $this->filterFields($this->source);
	}
}
