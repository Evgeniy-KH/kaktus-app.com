<?php

namespace App\Filters\Dish;

class TagFilter
{
    function __invoke($query, $request)
    {
        dump( $query);
        $tag_id = $request;
        $filter = function ($q) use ($tag_id) {
            $q->where('tag_id', $tag_id);
        };
        dd($query);
       $query->whereHas('tags', $filter)->with('dishImages', 'tags');
    }
}
