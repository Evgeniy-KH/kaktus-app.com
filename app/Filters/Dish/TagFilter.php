<?php

namespace App\Filters\Dish;

class TagFilter
{
    function __invoke($query, $request)
    {
        $tag_id= $request->tag_id;

        $filter = function ($q) use ($tag_id) {
            $q->where('tag_id', $tag_id);
        };

        return $query->whereHas('dishes', $filter)->with(['dishes' => $filter]);
    }
}
