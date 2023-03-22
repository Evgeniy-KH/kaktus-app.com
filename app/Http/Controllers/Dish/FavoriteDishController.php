<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Requests\Dish\AddToFavoriteDishRequest;
use App\Http\Resources\MessageResource;
use Illuminate\Http\JsonResponse;

class FavoriteDishController extends BaseController
{

    public final function addToFavoriteDish(AddToFavoriteDishRequest $request): MessageResource|JsonResponse
    {
        $dishId = $request->DTO()->getId();
        $dish = auth()->user()->favoriteDishes()->updateOrCreate(['dish_id' => $dishId]);

        if (!$dish) {
            return (new MessageResource([
                'success' => false,
                'message' => 'Failed to create favorite'
            ]))->response()
                ->setStatusCode(500); //500 Internal Server Error
        } else {
            return new MessageResource([
                "success" => true,
            ]);
        }
    }

    public final function removeFromFavoriteDish(AddToFavoriteDishRequest $request): MessageResource|JsonResponse
    {
        $dishId = $request->DTO()->getId();;
        // $dish= auth()->user()->favoriteDishes()->where('dish_id', $dishId)->delete();
        $dish = auth()->user()->favoriteDishes()->findById(dishId: $dishId)->delete();

        if (!$dish === '1') {
            return (new MessageResource([
                'success' => false,
                'message' => 'Failed to create favorite'
            ]))->response()
                ->setStatusCode(500); //500 Internal Server Error
        } else {
            return new MessageResource([
                "success" => true,
            ]);
        }
    }
}
