<?php
declare(strict_types=1);

namespace App\Service;

use App\Models\Dish;
use Illuminate\Support\Collection;


class LikeService
{
    public final function getDishes(object $likedDishes): Collection
    {
        $likedDishesId = [];

        foreach ($likedDishes as $likedDish) {
            array_push($likedDishesId, $likedDish['likeable_id']);
        }

        return Dish::with('dishImages', 'tags')->whereIn('id', $likedDishesId)->get();
    }
}

