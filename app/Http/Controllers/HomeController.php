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

//        foreach ($dishes as $dish) {
//            $newDate = $dish->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $dish->created_at)->format('d/m/Y');
//            $dish['new_data'] = $newDate;
//        }

        $returnData = $dishes;

        return response()->json($returnData);
    }

}
