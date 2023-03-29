<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\DishResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\ResponseResource;
use App\Service\DishService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function __construct(protected DishService $service)
    {
    }

    public final function index(Request $request): JsonResponse|DishCollection|ErrorResource|ResponseResource
    {
        $dishes = $this->service->index(request: $request);

        return new ResponseResource(
            resource: !$dishes->isEmpty() ? new DishCollection($dishes) : '',
            message:   !$dishes->isEmpty() ? '' : 'Your your filter doesn\'t\ match any dishes',
            statusCode: !$dishes->isEmpty() ? 200 : 404
        );
    }

    public final function show(int $id): DishResource
    {
        return new DishResource($this->service->show(id: $id));
    }

    public final function store(StoreRequest $request): JsonResponse|ResponseResource
    {
        $dish = $this->service->store(dto: $request->DTO());

        return new ResponseResource(
            resource: $dish->exists() ? new DishResource($dish) : '',
            message:  $dish->exists() ? 'You dish have been successfully stored' : 'Failed to store dish',
            statusCode: $dish->exists() ? 200 : 500
        );
    }

    public final function edit(int $id): DishResource
    {
        return new DishResource($this->service->getData(id: $id));
    }

    public final function update(int $id, UpdateRequest $request): JsonResponse|ResponseResource
    {
        $dish = $this->service->update(dto: $request->dto(), id: $id);

        return new ResponseResource(
            resource: $dish->exists() ? new DishResource($dish) : '',
            message:  $dish->exists() ? 'You dish have been successfully updated' : 'Failed to update',
            statusCode: $dish->exists() ? 200 : 500
        );
    }

    public final function delete(int $id): JsonResponse|ResponseResource
    {
        $result = $this->service->delete(id: $id);

        return new ResponseResource(
            message:  $result ? 'You dish have been successfully delete' : 'Failed to delete',
            statusCode: $result ? 200 : 500
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
