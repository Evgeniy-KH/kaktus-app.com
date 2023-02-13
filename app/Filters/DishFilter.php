<?php

namespace App\Filters;

use App\Filters\Dish\KeywordFilter;
use App\Filters\Dish\PriceFilter;
use App\Filters\Dish\TagFilter;

class DishFilter
{
    protected $filters = [
        'price' => PriceFilter::class,
        'tagsId' => TagFilter::class,
        'keyword' => KeywordFilter::class,
    ];

    public function apply($query)
    {
        foreach ($this->receivedFilters() as $name => $value) {
            $filterInstance = new $this->filters[$name];
            $query = $filterInstance($query, $value);
        }

        return $query;
    }


    public function receivedFilters()
    {
        return request()->only(array_keys($this->filters));
    }
}
