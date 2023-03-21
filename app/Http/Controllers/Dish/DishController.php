<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Service\DishService;
use Illuminate\Http\JsonResponse;

class DishController
{
    public function __construct(
        protected DishService $service
    )
    {
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $dishDto = $request->DTO();
        //TODO полный бред!!! Ты делаешь запись, но не проверяешь результат, и вслучае падения или ошибки, ты всё вернешь ответ.!!!
        $result = $this->service->store($dishDto);

        if ($result) {
            //TODO resource laravel
            return response()->json(["data" => $result, "success" => true]);
        }

        return response()->json(["success" => false]);
    }

    public function edit(int $id): JsonResponse
    {
        //TODO ресурс
        return response()->json($this->service->getData(id: $id));
    }

    public function update(int $id, UpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $dishDto = $request->DTO();
        $dish = $this->service->update($dishDto, $id);

        return response()->json();
    }

    public function delete(int $id): \Illuminate\Http\JsonResponse
    {
        //TODO сервис,
        $result = $this->service->deleteData($id);

        if ($result) {
            return response()->json(["success" => true]);
        }

        return response()->json(["success" => false]);
    }

    //    public function create(): View
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
