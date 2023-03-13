<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use http\QueryString;

class DishFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function keyword(string $keyword)
    {
        return $this->where(function ($q) use ($keyword) {
            return $q->where('title', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%");
        });
    }

    public function price(string $price)
    {
        list($min, $max) = explode(",", $price);

        return $this->where('price', '>=', $min)
            ->where('price', '<=', $max)
            ->with('dishImages', 'tags');
    }

    public function tagsId(string $tagsId)
    {
        $filter = function ($q) use ($tagsId) {
            $q->where('tag_id', $tagsId);
        };

        return $this->whereHas('tags', $filter);
    }

}
