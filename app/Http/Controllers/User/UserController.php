<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Filters\Dish\DishFilter;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\Service\UserService;

class UserController extends BaseController
{

    public function __construct(
        protected UserService $userService
    )
    {
        parent::__construct($userService);
    }

    public function update(int $id, UpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $userDto = $request->DTO();
        $returnData = $this->service->update( $userDto, $id);

        return response()->json($returnData);
    }

    public function getFavoriteDishes(): \Illuminate\Http\JsonResponse
    {
        $favoriteDishes = auth()->user()->favoriteDishes()->get();

        return response()->json($favoriteDishes);
    }

    public function myFavoritesDishes(DishFilter $filters): \Illuminate\Http\JsonResponse
    {
        $favoriteDishes = auth()->user()->favoriteDishes()->get();
        $favoriteDishesArray =  $favoriteDishes->toArray();
        $returnData = $this->service->favoriteDishes($favoriteDishesArray);

        return response()->json($returnData);
    }

    public function usersDishes(): \Illuminate\Http\JsonResponse
    {
        $usersDishes = auth()->user()->dishes()->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(8);
        $returnData = $usersDishes;

        return response()->json($returnData);
    }

    /* public function favoritesDishes()
 {
     return view('favorites-dishes');
 }*/
}
