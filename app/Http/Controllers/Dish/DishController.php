<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Http\Resources\DishResource;
use App\Http\Resources\MessageResource;
use App\Service\DishService;
use Illuminate\Http\JsonResponse;

class DishController
{
    public function __construct(
        protected DishService $service
    )
    {
    }

    public final function store(StoreRequest $request): MessageResource|JsonResponse
    {
        $dishDto = $request->DTO();
        //TODO полный бред!!! Ты делаешь запись, но не проверяешь результат, и вслучае падения или ошибки, ты всё вернешь ответ.!!!
        $result = $this->service->store($dishDto);

        if ($result) {
            return new MessageResource([
                "success" => true,
                'message' => 'You dish have been successfully stored'
            ]);
            // return response()->json(["data" => $result, "success" => true]);
        } else {
            return (new MessageResource([
                'success' => false,
                'message' => 'Failed to create favorite'
            ]))->response()
                ->setStatusCode(500);
        }
    }

    public final function edit(int $id): DishResource
    {
        return new DishResource($this->service->getData(id: $id));
    }

    public final function update(int $id, UpdateRequest $request): JsonResponse|MessageResource
    {
        $dishDto = $request->DTO();
        $dish = $this->service->update($dishDto, $id);

        if ($dish) {
            return new MessageResource([
                "success" => true,
                'message' => 'You dish have been successfully stored'
            ]);
        } else {
            return (new MessageResource([
                'success' => false,
                'message' => 'Failed to create favorite'
            ]))->response()
                ->setStatusCode(500);
        }
    }

    public final function delete(int $id): JsonResponse|MessageResource
    {
        //TODO сервис,
        $result = $this->service->deleteData($id);

        if ($result) {
            return new MessageResource([
                "success" => true,
                'message' => 'You dish have been successfully delete'
            ]);
        } else {
            return (new MessageResource([
                'success' => false,
                'message' => 'Failed to create favorite'
            ]))->response()
                ->setStatusCode(500);
        }
        // return response()->json(["success" => false]);
    }

    //    public final function create(): View
//    {
//        $user = Auth::user();
//
//        return view('dish.create', compact('user'));
//    }

//    public function editView(int $dishId)
//    {
//        return view('dish.edit', compact('dishId'));
//    }
}
