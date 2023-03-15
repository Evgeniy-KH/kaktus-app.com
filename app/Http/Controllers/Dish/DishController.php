<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Models\Dish;
use App\Models\Tag;
use App\Service\DishService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DishController 
{
    public function __construct(
        protected DishService $dishService
    )
    {
    }

    public function store(StoreRequest $request): JsonResponse
    {
        //TODO полный бред!!! Ты делаешь запись, но не проверяешь результат, и вслучае падения или ошибки, ты всё вернешь ответ.!!!

         $result = $this->service->store($request->validated());
        
         if ($result) {
            //TODO resource laravel
            return response()->json(["data" => result, "success" => true]);
         }

         return response()->json(["success" => false]);
    }

    public function edit(int $dishId): JsonResponse
    {
        //TODO ресурс
        return response()->json($this->service->getData(id: $dishId));
    }

    public function update(int $id, UpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        //DTO передаём.
        $dish = $this->service->update($data, $id);

        return response()->json();
    }

    public function delete(int $dishId): \Illuminate\Http\JsonResponse
    {
        //TODO сервис,
        DB::transaction(function () use ($dishId) {
            auth()->user()->favoriteDishes()->where('dish_id', $dishId)->delete();
            Dish::findOrFail($dishId)->delete();
        });
        
        return response()->json();
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
