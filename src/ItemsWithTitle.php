<?php

namespace Tiuswebs\ConstructorCore;

/*
Load cruds with title and image
*/

class ItemsWithTitle extends Result
{
    public $default_limit = 6;
    public $include_options = ['products', 'partners', 'promotions', 'videos', 'multimedia', 'news'];
}