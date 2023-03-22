<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Http\Requests\UnlikeRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\DishResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\Dish;
use App\Models\User;
use App\Service\LikeService;
use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LikeController extends Controller
{
    public function __construct(
        protected LikeService $service
    )
    {
    }

    public final function like(LikeRequest $request): MessageResource|JsonResponse
    {
        $user = $request->user()->like($request->likeable());

        return $this->returnData(dataReturn: $user);
    }

    public final function unlike(UnlikeRequest $request): MessageResource|JsonResponse
    {
        $user = $request->user()->unlike($request->likeable());

        return $this->returnData(dataReturn: $user);
    }

    public final function users(Request $request): AnonymousResourceCollection
    {
        $usersId = $request->usersId;
        $users = User::find(id: $usersId)->take(4);

        return UserResource::collection($users);
    }

    public final function likedDishes(): DishCollection
    {
        $likedDishes = auth()->user()->likes()->where('likeable_type', '=', 'App\\Models\\Dish')->get();
        $dishes = $this->service->getDishes(likedDishes: $likedDishes);

        if (!$dishes) {
            $dishes = array();
        }

        return new DishCollection($dishes);
        // return response()->json($returnData);
    }

    public final function returnData(object $dataReturn): MessageResource|JsonResponse
    {
        if ($dataReturn) {
            return new MessageResource([
                "success" => true,
            ]);
        } else {
            return (new MessageResource([
                'success' => false,
            ]))->response()
                ->setStatusCode(500);
        }
    }
}
