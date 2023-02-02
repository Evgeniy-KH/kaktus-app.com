<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\StoreRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class DishController extends Controller
{
    public function create(): View
    {
        $user = Auth::user();
        return view('recipes.create', compact('user'));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        dd($data);


    }

}
