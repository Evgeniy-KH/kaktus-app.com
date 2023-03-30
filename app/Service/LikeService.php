<?php
declare(strict_types=1);

namespace App\Service;

use App\Contracts\Likeable;
use App\Http\Requests\UnlikeRequest;
use App\Http\Resources\ResponseResource;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;


class LikeService
{

    public final function store(Likeable $likeable): User
    {
        return auth()->user()->like($likeable);
    }

    public final function delete(Likeable $likeable): User
    {
        return auth()->user()->unlike($likeable);
    }

    public final function showUsers(int $id): User
    {
        return User::find(id: $id)->take(4);
    }

    public final function showDishes(int $id): \Illuminate\Database\Eloquent\Collection
    {
//        $likedDishes = auth()->user()->likes()->dishLikes()->get();
//        $dishes = $this->service->getDishes(likedDishes: $likedDishes);
//
//        if (!$dishes) {
//            $dishes = array();
//        }

        //1. Поменять на with
        //2. СКАЗАТЬ ТОЧНО И ОТЧËТЛИВО чем будет отличаться запрос, результат и форма результата при запросе с with  и join.
        return Dish::select('dishes.*')
            ->join('likes', 'likes.likeable_id', '=', 'dishes.id')
            //TODO какой то бред.
            ->where('likes.likeable_type', 'App\Models\Dish')
            ->where('likes.user_id', $id)
            ->with('dishImages', 'tags')
            ->get();
    }

    public final function getDishes(object $likedDishes): Collection
    {
        $likedDishesId = [];

        foreach ($likedDishes as $likedDish) {
            array_push($likedDishesId, $likedDish['likeable_id']);
        }

        return Dish::with('dishImages', 'tags')->whereIn('id', $likedDishesId)->get();
    }
}

