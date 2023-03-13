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
        $user = User::find($id);
        $data = $request->validated();
        $returnData = $this->service->update($data, $user);

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
        $favoriteDishes =  $favoriteDishes->toArray();

        $returnData = $this->service->favoriteDishes($favoriteDishes);


//        foreach ($favoriteDishes as $favoriteDish) {
//            array_push($favoriteDishesId, $favoriteDish['dish_id']);
//        }
//
//        $returnData = Dish::with('dishImages', 'tags')->whereIn('id', $favoriteDishesId)->paginate(8);
//        $code = 200;
//
//        if ($returnData->isEmpty()) {
//            $returnData = array(
//                'status' => 'error',
//                'message' => 'Your your filter doesn\'t\ match any dishes'
//            );
//            $code = 422;
//        }

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
