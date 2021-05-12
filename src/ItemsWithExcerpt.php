<?php

namespace Tiuswebs\ConstructorCore;

/*
Load cruds with title, image, description, excerpt
*/

class ItemsWithExcerpt extends Result
{
    public $default_limit = 6;
    public $include_options = ['products', 'partners', 'promotions', 'news', 'portfolios', 'blog_entries', 'categories'];
}