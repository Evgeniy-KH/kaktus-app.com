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

class DishController extends BaseController
{
    public function __construct(
        protected Dish        $dish,
        protected Tag         $tag,
        protected DishService $dishService
    )
    {
        parent::__construct($dishService);
    }

    public function store(StoreRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $this->service->store($data);

        return response()->json();
    }

    public function edit(int $dishId): \Illuminate\Http\JsonResponse
    {
        $dish = $this->dish->with('dishImages')->findOrFail($dishId);
        $tags = $this->tag->all();
        $returnData = [$dish, $tags];

        return response()->json($returnData);
    }

    public function update(UpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $dish = $this->dish->findOrFail($data['dish_id']);
        unset($data['dish_id']);

        $dish = $this->service->update($data, $dish);

        return response()->json();
    }

    public function delete(int $dishId): \Illuminate\Http\JsonResponse
    {
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
