<?php

namespace Tiuswebs\ConstructorCore;

use Illuminate\Support\Facades\Cache;

class Pagination extends Core
{
    public $category = 'pagination';
    public $page = 0;

    public function load()
    {
        parent::load();

        // Save page and size
        $this->page = request()->page ?? 0;
        $this->size = Cache::store('array')->get('filter-contents-size', 0);

        // Save number of current items loaded
        $items = Cache::store('array')->get('filter-contents', []);
        $items = collect($items)->count();
        $this->current_items = $items;

        // Dont load component if there is 0 elements with the "Content" option
        if ($this->size <= 0) {
            $this->show_view = false;
        }
    }

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
        if ($type == '+') {
            $page = $this->page + 1;
        } else {
            $page = $this->page - 1;
        }
        if ($page < 0) {
            return;
        }
        if ($type == '+' && $this->current_items != $this->size) {
            return;
        }
        return url()->current() . '?page=' . $page . '&size=' . $this->size;
    }
}
