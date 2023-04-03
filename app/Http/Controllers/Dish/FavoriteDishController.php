<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Filters\Dish\DishFilter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\BaseController;
use App\Http\Requests\Dish\AddToFavoriteDishRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\FavoriteDishIdResource;
use App\Http\Resources\ResponseResource;
use App\Models\Dish;
use App\Models\FavoriteDish;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class FavoriteDishController extends Controller
{
    public function __construct(
        protected UserService  $userService,
        protected FavoriteDish $favoriteDishes
    )
    {
    }

    //store
    public final function store(AddToFavoriteDishRequest $request): ResponseResource|JsonResponse
    {
        $dish =auth()->user()->favoriteDishes()->updateOrCreate(['dish_id' => $request->dto()->getId()]);

//        if (!$dish) {
//            return new MessageResource(message: 'Failed to create favorite', statusCode: 500);
//        } else {
//            return new MessageResource(resource: $dish, message: 'Store successfully', statusCode: 200);
//        }
        return new ResponseResource(
            resource: !$dish  ? '' : $dish,
            message:  !$dish  ? 'Failed to create favorite' : 'Store successfully',
            statusCode:!$dish  ?  500 : 200
        );
    }

    public final function delete(AddToFavoriteDishRequest $request): ResponseResource|JsonResponse
    {
        $dishId = $request->dto()->getId();;
        auth()->user()->favoriteDishes()->findById(dishId: $dishId)->delete();
        $isSuccess = auth()->user()->favoriteDishes()->findById(dishId: $dishId)->doesntExist();

        return new ResponseResource(
            message: $isSuccess ? 'Deleted successfully' : 'Try again later',
            statusCode: $isSuccess ? 200 : 404
        );
    }

    public final function show(): AnonymousResourceCollection|ResponseResource
    {
        $favoriteDishesId = auth()->user()->favoriteDishes()->get();

        return new ResponseResource(
            resource: FavoriteDishIdResource::collection($favoriteDishesId),
        );
    }

    public final function index(DishFilter $filters): ResponseResource
    {
        $dishes = Dish::select('dishes.*')
            ->join('favorite_dishes', 'favorite_dishes.dish_id', '=', 'dishes.id')
            ->where('favorite_dishes.user_id', auth()->user()->id)
            ->with('dishImages', 'tags')
            ->paginate(8);

        return new ResponseResource(
            resource: !$dishes->isEmpty() ? new DishCollection($dishes) : '',
            message:   !$dishes->isEmpty() ? '' : 'Your list of favorites dishes are empty',
        );
    }
}
