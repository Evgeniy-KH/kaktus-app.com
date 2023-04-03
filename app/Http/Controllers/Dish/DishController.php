<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\DishResource;
use App\Http\Resources\ResponseResource;
use App\Service\DishService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function __construct(protected DishService $service)
    {
    }

    public final function index(Request $request): ResponseResource
    {
        $dishes = $this->service->index(request: $request);
        $isDishEmpty = $dishes->isEmpty();

        return new ResponseResource(
            resource: !$isDishEmpty ? new DishCollection($dishes) : null,
            message: !$isDishEmpty ? '' : 'Your your filter doesn\'t\ match any dishes',
            statusCode: !$isDishEmpty ? 200 : 404
        );
    }

    public final function show(int $id): ResponseResource
    {
        return new ResponseResource(
            resource: new DishResource($this->service->show(id: $id))
        );
    }

    public final function store(StoreRequest $request): JsonResponse|ResponseResource
    {
        $dish = $this->service->store(dto: $request->DTO());
        $isExistsDish = $dish->exists();

        return new ResponseResource(
            resource: $isExistsDish ? new DishResource($dish) : null,
            message: $isExistsDish ? 'You dish have been successfully stored' : 'Failed to store dish',
            statusCode: $isExistsDish ? 200 : 500
        );
    }

    public final function edit(int $id): ResponseResource
    {
        return new ResponseResource(
            resource: new DishResource($this->service->show(id: $id))
        );
    }

    public final function update(int $id, UpdateRequest $request): ResponseResource
    {
        $dish = $this->service->update(dto: $request->dto(), id: $id);
        $isExistsDish = $dish->exists();

        return new ResponseResource(
            resource: $isExistsDish ? new DishResource($dish) : null,
            message: $isExistsDish ? 'You dish have been successfully updated' : 'Failed to update',
            statusCode: $isExistsDish ? 200 : 500
        );
    }

    public final function delete(int $id): ResponseResource
    {
        $this->service->delete(id: $id);

        return new ResponseResource(
            message: 'You dish have been successfully delete',
        );
    }
}


//        if (!$dishes->isEmpty()) {
//            return new DishCollection($dishes);
//        } else {
//            return new MessageResource( message: 'Your your filter doesn\'t\ match any dishes', statusCode: 404);
////            return (new MessageResource([
////                "success" => !$dishes->isEmpty(),
////                "message" => !$dishes->isEmpty() ? '' : 'Your your filter doesn\'t\ match any dishes',
////                "data" => '',
////            ]))->response()
////                ->setStatusCode(!$dishes->isEmpty() ? 200 : 404);
//        }

//        return (new MessageResource([
//            "message" => !$dishes->isEmpty() ? '' : 'Your your filter doesn\'t\ match any dishes',
//            "data" => !$dishes->isEmpty() ? new DishCollection($dishes) : '',
//        ]))->response()
//            ->setStatusCode(!$dishes->isEmpty() ? 200 : 404);
