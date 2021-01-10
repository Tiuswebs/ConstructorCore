<?php

namespace Tiuswebs\ConstructorCore;

class Blog extends Result
{
    public $category = 'blog';
    public $default_limit = 6;
    public $include_options = ['products', 'jobs', 'partners', 'promotions'];
}