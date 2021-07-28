<?php

namespace Tiuswebs\ConstructorCore;

class Pagination extends Core
{
    public $category = 'pagination';

    public function getPreviousUrl()
    {
        return $this->getUrl('-');
    }

    public function getNextUrl()
    {
        return $this->getUrl('+');
    }

    public function getUrl($type = '+')
    {
        return '#';
    }
}
