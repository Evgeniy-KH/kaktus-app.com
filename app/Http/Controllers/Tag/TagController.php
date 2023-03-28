<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\DishResource;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Http\Resources\TagResource;
use App\Models\Dish;
use App\Models\Tag;
use App\Service\DishService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Psy\Util\Json;

class TagController extends Controller
{
    //Dish $dish этого не должно быть!!!!!!!!!!!!! Или у тебя сервис или у тебя модель. Но не сервис и работа с моделью
    //protected Tag  $tag так же само и это. Но тут подумать нужно. У тебя сервис,


    public function __construct(protected DishService $service)
    {
    }

    public final function index(Request $request): JsonResponse|DishCollection
    {
        $dishes = $this->service->index(request: $request);

        if (!$dishes->isEmpty()) {
            return new DishCollection($dishes);
        } else {
            return (new MessageResource([
                "success" => !$dishes->isEmpty(),
                "message" => !$dishes->isEmpty() ? '' : 'Your your filter doesn\'t\ match any dishes',
                "data" => '',
            ]))->response()
                ->setStatusCode(!$dishes->isEmpty() ? 200 : 404);
        }
//        return (new MessageResource([
//            "success" => !$dishes->isEmpty(),
//            "message" => !$dishes->isEmpty() ? '' : 'Your your filter doesn\'t\ match any dishes',
//            "data" => !$dishes->isEmpty() ? new DishCollection($dishes) : '',
//        ]))->response()
//            ->setStatusCode(!$dishes->isEmpty() ? 200 : 404);
    }

    public final function show(int $id): DishResource
    {
        return new DishResource($this->service->show(id: $id));
    }

    public final function store(StoreRequest $request): JsonResponse|MessageResource
    {
        $dish = $this->service->store(dto: $request->DTO());

        return (new MessageResource([
            "success" => $dish->exists(),
            "message" => $dish->exists() ? 'You dish have been successfully stored' : 'Failed to create favorite',
            "data" => $dish->exists() ? new DishResource($dish) : '',
        ]))->response()
            ->setStatusCode($dish->exists() ? 200 : 500);
    }

    public final function edit(int $id): DishResource
    {
        return new DishResource($this->service->getData(id: $id));
    }

    public final function update(int $id, UpdateRequest $request): JsonResponse|MessageResource
    {
        $dish = $this->service->update(dto: $request->dto(), id: $id);

        return (new MessageResource([
            "success" => $dish->exists(),
            "message" => $dish->exists() ? 'You dish have been successfully updated' : 'Failed to update',
            "data" => $dish->exists() ? new DishResource($dish) : '',
        ]))->response()
            ->setStatusCode($dish->exists() ? 200 : 500);
    }

    public final function delete(int $id): JsonResponse|MessageResource
    {
        $result = $this->service->delete(id: $id);

        return (new MessageResource([
            "success" =>$result,
            "message" => $result ? 'You dish have been successfully delete' : 'Failed to delete']))->response()
            ->setStatusCode($result ? 200 : 500);
    }

    //controller tag index.
    public final function tags(): AnonymousResourceCollection
    {
        // return TagResource::collection($this->tag->all());
    }
}
