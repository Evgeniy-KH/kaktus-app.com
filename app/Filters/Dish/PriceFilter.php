<?php

namespace App\Filters\Dish;

class PriceFilter
{
    public function apply($query)
    {
        if (request()->filled('price')) {
            list($min, $max) = explode(",", $request->price);

            $query->where('price', '>=', $min)
                ->where('price', '<=', $max);
        }


        if (request()->filled('tags')) {
            $categorySlug = $request->category;

            $query->whereHas('tags', function ($query) use ($tagSlug) {
                $query->where('slug', $tagSlug);
            });
        }


        return $query->get();
    }
}
