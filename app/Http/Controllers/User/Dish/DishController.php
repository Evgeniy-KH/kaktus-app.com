<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dish;


use App\Filters\DishFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Models\Dish;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DishController extends BaseController
{

    public function create(): View
    {
        $user = Auth::user();

        return view('dish.create', compact('user'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $this->service->store($data);

        return response()->json();
    }

    public function editView(int $dishId)
    {
        return view('dish.edit', compact('dishId'));
    }

    public function editData(int $dishId)
    {
        $dish = Dish::with('dishImages')->findOrFail($dishId);
        $tags = Tag::all();
        $returnData = [$dish, $tags];

        return response()->json($returnData);
    }

    public function update(UpdateRequest $request)
    {
        $data = $request->validated();
        $dish = Dish::findOrFail($data['dish_id']);
        unset($data['dish_id']);

        $dish = $this->service->update($data, $dish);

        return response()->json();
    }

    public function delete(int $dishId)
    {
        DB::transaction(function () use ($dishId) {
            auth()->user()->favoriteDishes()->where('dish_id', $dishId)->delete();
            Dish::findOrFail($dishId)->delete();
        });

        return response()->json();
    }

}
