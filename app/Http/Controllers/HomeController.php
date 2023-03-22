<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Filters\Dish\DishFilter;
use App\Http\Resources\DishCollection;
use App\Http\Resources\DishResourceOld;
use App\Http\Resources\DishResourceCollectionOld;
use App\Models\Dish;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct
    (
        protected Dish $dish,
        protected Tag  $tag)
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

    public function tags(): \Illuminate\Http\JsonResponse
    {
        $tags = $this->tag->all();

        return response()->json($tags);
    }

    public function catalog(Request $request)
    {
        $dishes = $this->dish->filter($request->all())->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(4);
       // return response()->json($dishes);
        return new DishCollection($dishes);

//        if ($dishes->isEmpty()) {
//            return response()->json([
//                'message' => 'Your your filter doesn\'t\ match any dishes','code'
//            ], 404);
//        }
////
//       //   return new DishResource($dishes);
//        return DishResourceCollectionOld::collection($dishes);
    }

    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $dish = $this->dish->with('dishImages')->findOrFail($id);

        return response()->json($dish);
    }

//    public function show(int $id)
//    {
//        return view('dish', compact('id'));
//    }
}
