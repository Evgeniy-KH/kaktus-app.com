<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dish;


use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\StoreRequest;
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

}
