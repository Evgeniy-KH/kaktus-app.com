<?php
declare(strict_types=1);

namespace App\Service;

use App\Data\Dish\DishLikedDto;
use App\Models\Dish;
use App\Models\DishImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Collection;

class LikeService
{
    public final function getDishes(object $likedDishes): \Illuminate\Database\Eloquent\Collection
    {
        $likedDishesId = [];

        foreach ($likedDishes as $likedDish) {
            array_push($likedDishesId, $likedDish['likeable_id']);
        }

        return Dish::with('dishImages', 'tags')->whereIn('id', $likedDishesId)->get();
    }

//    public final function DTO($data): DishLikedDto
//    {
//        dd($data);
//        return new DishLikedDto($data('dish_id'),$data('likeable_type'),$data('likeable_id'));
//    }
}

