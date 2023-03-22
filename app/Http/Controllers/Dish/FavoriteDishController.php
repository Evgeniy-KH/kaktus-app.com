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

        if(!$dish) {
            $response = [
                'status' => false,
                'message' => 'Failed to create favorite'
            ];
            return (new MessageResource($response))->response()
                ->setStatusCode(500); //500 Internal Server Error
        } else {
            $response = [
                'status' => 'success',
            ];

            return (new MessageResource($response));
        }
    }

    public final function removeFromFavoriteDish(AddToFavoriteDishRequest $request): MessageResource|JsonResponse
    {
        $dishId = $request->DTO()->getId();;
        $dish= auth()->user()->favoriteDishes()->where('dish_id', $dishId)->delete();

        if(!$dish === '1') {
            $response = [
                'status' => false,
                'message' => 'Failed to create favorite'
            ];
            return (new MessageResource($response))->response()
                ->setStatusCode(500); //500 Internal Server Error
        }else {
            $response = [
                'status' => 'success',
            ];
            return (new MessageResource($response));
        }
    }
}
