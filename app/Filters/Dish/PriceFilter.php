<?php

namespace App\Filters\Dish;

class PriceFilter
{
    function __invoke($query, $request)
    {
        list($min, $max) = explode(",", $request);
        $query->where('price', '>=', $min)
            ->where('price', '<=', $max)
        ->with('getDishImages', 'tags');
    }
}
