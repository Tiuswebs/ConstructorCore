<?php

namespace Tiuswebs\ConstructorCore\Inputs;

class BorderType extends Select
{
    public function load()
    {
        $this->options($this->loadOptions());
    }

    public function loadOptions()
    {
        return [
            'border-solid' =>  __('Solid'),
            'border-dashed' => __('Dashed'),
            'border-dotted' => __('Dotted'),
            'border-double' => __('Double'),
            'border-none' =>   __('None'),
        ];
    }
}
