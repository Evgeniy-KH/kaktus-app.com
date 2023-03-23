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
    public function __construct(protected DishService $service)
    {
    }

    // тут метод индекс. Тут метод ИНДЕКС.  И только тут будет логика что бы отдать все блюда для польхователя.!!!!!!


    public final function store(StoreRequest $request): JsonResponse
    {

        //TODO полный бред!!! Ты делаешь запись, но не проверяешь результат, и вслучае падения или ошибки, ты всё вернешь ответ.!!!
        $result = $this->service->store(dishDto: $request->DTO());

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
        
        //Не сильно логично. 
        // 200 ['status' => true, message => fdsfdsfdsf] 
        // 500 [ message => fdsfdsfdsf] 



    }

    public final function edit(int $id): DishResource
    {
        return new DishResource($this->service->getData(id: $id));
    }

    public final function update(int $id, UpdateRequest $request): JsonResponse|MessageResource
    {
        $dishDto = $request->DTO();
        //TODO  полменял что бы сломать класс.
        $dish = $this->service->update(dto: $dishDto, id: $id);

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
        $result = $this->service->deleteData(id: $id);

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
}
