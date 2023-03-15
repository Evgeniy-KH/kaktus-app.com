<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Filters\Dish\DishFilter;
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

    public function catalog(Request $request): \Illuminate\Http\JsonResponse
    {
        $returnData = $this->dish->filter($request->all())->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(4);
        // $returnData = Dish::filter($filters)->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(4);;
        $code = 200;

        if ($returnData->isEmpty()) {
            $returnData = array(
                'status' => 'error',
                'message' => 'Your your filter doesn\'t\ match any dishes'
            );
            $code = 422;
        }

        return response()->json($returnData, $code);
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
