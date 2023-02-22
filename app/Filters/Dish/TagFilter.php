<?php

namespace App\Filters\Dish;

class TagFilter
{
    function __invoke($query, $request)
    {
        $tag_id = $request;
        $filter = function ($q) use ($tag_id) {
            $q->where('tag_id', $tag_id);
        };

        return $query->whereHas('tags', $filter)->with('dishImages', 'tags');
    }
}
