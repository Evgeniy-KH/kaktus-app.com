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

        return $this->returnMessage(dataReturn: $user);
    }

    public final function unlike(UnlikeRequest $request): MessageResource|JsonResponse
    {
        $user = $request->user()->unlike($request->likeable());

        return $this->returnMessage(dataReturn: $user);
    }

    public final function users(Request $request): AnonymousResourceCollection
    {
        $usersId = $request->usersId;
        $users = User::find(id: $usersId)->take(4);

        return UserResource::collection($users);
    }

    public final function likedDishes(): AnonymousResourceCollection
    {
        $likedDishes = auth()->user()->likes()->dishLikes()->get();
        $dishes = $this->service->getDishes(likedDishes: $likedDishes);

        if (!$dishes) {
            $dishes = array();
        }

        return DishResource::collection($dishes);
    }

    public final function returnMessage(object $dataReturn): MessageResource|JsonResponse
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
