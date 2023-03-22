<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Filters\Dish\DishFilter;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\FavoriteDishIdResource;
use App\Http\Resources\MessageResource;
use App\Models\Dish;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends BaseController
{

    public function __construct(
        protected UserService $userService
    )
    {
        parent::__construct($userService);
    }

    public function update(int $id, UpdateRequest $request): MessageResource|JsonResponse
    {
        $userDto = $request->DTO();
        $returnData = $this->service->update(userDto: $userDto, id: $id);

        if ($returnData['success'] === true) {
            return new MessageResource([
                "success" => true,
                'message' => 'You dish have been successfully updated'
            ]);
        } else {
            return (new MessageResource([
                'success' => false,
                'message' => $returnData['message']
            ]))->response()
                ->setStatusCode($returnData['code']);
        }
    }

    public function getFavoriteDishes(): AnonymousResourceCollection
    {
        $favoriteDishes = auth()->user()->favoriteDishes()->get();

        return FavoriteDishIdResource::collection($favoriteDishes);
    }

    public function myFavoritesDishes(DishFilter $filters): DishCollection|JsonResponse
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

    public function usersDishes(): DishCollection
    {
        $usersDishes = auth()->user()->dishes()->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(8);

        return new DishCollection($usersDishes);
    }

}
