<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\FilterRequest;
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

    public final function index(FilterRequest $request): ResponseResource
    {
        $dishes = $this->service->list(dto: $request->dto());
        //TODO подумать почему это совсем не правильно и совсем не годится. ПРосто не правильно. И Дам подсказку для дауна, дело не в коде.!!!!!!!!!!!!!!
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

    public final function store(StoreRequest $request): ResponseResource
    {
        $dish = $this->service->store(dto: $request->dto());
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
        // Прошло оюновленгие или нет. 
        //Какой смысл проверять запиьс после обновления. 
        $isExistsDish = $dish->exists();

        return new ResponseResource(
            resource: new DishResource($dish),
            message: 'You dish have been successfully updated',
            statusCode: 200
        );
    }

    public final function delete(int $id): ResponseResource
    {
        $deleted = $this->service->delete(id: $id);

        return new ResponseResource(
            message: $deleted ? 'You dish have been successfully delete' : 'Failed to delete',
            statusCode: $deleted ? 200 : 500
        );
    }
}
