<?php
declare(strict_types=1);

namespace App\Filters\Dish;

use Illuminate\Database\Eloquent\Builder;

class KeywordFilter
{
    function __invoke(Builder $query, array $request): Builder 
    {
        //TODO тут должно быть сразу слово!!!!!!!!!! Никаких работс массивом.
        $keyword = $request[0];
        $query->where('title', 'LIKE', '%' . $keyword . '%')
             ->orWhere('description', 'LIKE', '%' . $keyword . '%')
            //узнать разницу между with и LOAD. 
            ->with('dishImages', 'tags');

        return $query;
    }
}
