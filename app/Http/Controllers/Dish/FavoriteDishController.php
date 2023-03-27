<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Filters\Dish\DishFilter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\BaseController;
use App\Http\Requests\Dish\AddToFavoriteDishRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\FavoriteDishIdResource;
use App\Http\Resources\MessageResource;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FavoriteDishController extends Controller
{
    public function __construct(
        protected UserService $userService
    )
    {
    }

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

    public final function getFavoriteDishesId(): AnonymousResourceCollection
    {
        $favoriteDishesId = auth()->user()->favoriteDishes()->get();

        return FavoriteDishIdResource::collection($favoriteDishesId);
    }

    public final function myFavoritesDishes(DishFilter $filters): DishCollection|JsonResponse
    {
        $favoriteDishes = auth()->user()->favoriteDishes()->get();
        $returnData = $this->service->favoriteDishes(favoriteDishesArray: $favoriteDishes->toArray());

        if ($returnData['success'] === true) {
            return new DishCollection($returnData['data']);
        } else {
            return (new MessageResource([
                'success' => false,
                'message' => $returnData['message']
            ]))->response()
                ->setStatusCode($returnData['code']);
        }
    }
}
