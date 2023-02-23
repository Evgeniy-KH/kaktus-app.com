<?php

namespace App\Filters\Dish;

class KeywordFilter
{
    function __invoke($query, $request)
    {
        $keyword = $request[0];
        $query->where('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('description', 'LIKE', '%' . $keyword . '%')
            ->with('dishImages', 'tags');

        return $query;
    }
}
