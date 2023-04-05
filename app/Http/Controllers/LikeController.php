<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Http\Requests\UnlikeRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\DishResource;
use App\Http\Resources\ResponseResource;
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
        protected readonly LikeService $service
    )
    {
    }

    public final function store(LikeRequest $request): ResponseResource
    {
        $user = $this->service->store(likeable: $request->likeable());

        return new ResponseResource(resource: $user);
    }

    public final function delete(int $userId, int $dishId, UnlikeRequest $request): ResponseResource|JsonResponse
    {
        $user = $this->service->delete(id:$userId,  likeable: $request->likeable());

        return new ResponseResource(resource: $user);
    }

    public final function show(int $id): ResponseResource
    {
        $dishes = $this->service->showDishes(id: $id);

        return new ResponseResource(resource: DishResource::collection($dishes));

    }
}
