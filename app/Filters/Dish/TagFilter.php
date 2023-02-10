<?php

namespace App\Filters\Dish;

class TagFilter
{
    function __invoke($query, $request)
    {
        list($min, $max) = explode(",", $request);
        $query->where('price', '>=', $min)
            ->where('price', '<=', $max)
        ->with('getDishImages');
    }
}
