<?php

namespace App\Http\Controllers;

use App\Filters\DishFilter;
use App\Http\Requests\Dish\FilterRequest;
use App\Models\Dish;
use App\Models\DishImage;
use App\Models\Tag;
use Illuminate\Http\Request;
use Carbon\Carbon;
use function PHPUnit\Framework\isEmpty;

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
    public function index(): \Illuminate\Contracts\Support\Renderable
    {
        return view('home');
    }

    public function tags()
    {
        $tags = Tag::all();

        return response()->json($tags);
    }

    public function catalog(DishFilter $filters)
    {
        $returnData = Dish::filter($filters)->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(8);
        $code = 200;

        if ($returnData->isEmpty()) {
            $returnData = array(
                'status' => 'error',
                'message' => 'Your your filter doesn\'t\ match any dishes'
            );
            $code = 422;
        }

        return response()->json($returnData,$code);
    }

    public function show(int $id)
    {
        return view('dish', compact('id'));
    }

    public function showDish(int $id)
    {
        $dish = Dish::with('dishImages')->findOrFail($id);

        return response()->json($dish);
    }
}
