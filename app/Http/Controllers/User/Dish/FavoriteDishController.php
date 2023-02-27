<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dish;


use App\Http\Requests\Dish\AddToFavoriteDishRequest;
use App\Models\FavoriteDish;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class FavoriteDishController extends BaseController
{

    public function addToFavoriteDish(AddToFavoriteDishRequest $request)
    {
        $data = $request->validated();
        auth()->user()->favoriteDishes()->updateOrCreate($data);

        return response()->json();
    }

    public function removeFromFavoriteDish(AddToFavoriteDishRequest $request)
    {
        $data = $request->validated();
        $dishId = $data['dish_id'];
        auth()->user()->favoriteDishes()->where('dish_id', $dishId)->delete();

        return response()->json();
    }
}
