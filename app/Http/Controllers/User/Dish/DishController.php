<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dish;


use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Models\Dish;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

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
        $dish = Dish::with('getDishImages')->findOrFail($dishId);
        $tags = Tag::all();
        $returnData = [$dish, $tags];

        return response()->json($returnData);
    }

    public function update(StoreRequest $request)
    {
        dd($request->getContent());
        $data = $request->validated();
        dd($data);
        $this->service->store($data);

        return response()->json();
    }

}
