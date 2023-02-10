<?php

namespace App\Filters;

use App\Filters\Dish\PriceFilter;

class DishFilter
{
    protected $filters = [
        'price' => PriceFilter::class,
        ///'tags' => CategoryFilter::class,
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
