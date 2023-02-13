<?php

namespace App\Filters\Dish;

class KeywordFilter
{
    function __invoke($query, $request)
    {
        $keyword = $request;

        $query->where('title', 'LIKE', '%' .$keyword. '%')
            ->orWhere('description', 'LIKE', '%' .$keyword. '%')
            ->with('getDishImages', 'tags');
    }
}
