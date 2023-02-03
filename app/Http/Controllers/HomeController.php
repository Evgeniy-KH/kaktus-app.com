<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\DishImage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function catalog()
    {
        $dishes = Dish::with('getDishImages')->get();

        $returnData = $dishes;

        return response()->json($returnData);
    }

    public function show(int $id)
    {
        return view('dish', compact('id'));
    }

    public function showDish(int $id)
    {
        $dish = Dish::with('getDishImages')->findOrFail($id);

        return response()->json($dish);
    }
}
