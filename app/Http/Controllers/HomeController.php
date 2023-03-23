<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\DishCollection;
use App\Http\Resources\DishResource;
use App\Http\Resources\TagResource;
use App\Models\Dish;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    public function __construct
    (
        protected Dish $dish,
        protected Tag  $tag)
    {
        $this->middleware('auth');
    }

    public final function index(): Renderable
    {
        return view('home');
    }

    public final function tags(): AnonymousResourceCollection
    {
        $tags = $this->tag->all();

        return TagResource::collection($tags);
    }

    public final function catalog(Request $request): DishCollection|JsonResponse
    {
        $dishes = $this->dish->filter($request->all())->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(4);

        if ($dishes->isEmpty()) {
            return response()->json([
                'message' => 'Your your filter doesn\'t\ match any dishes', 'code'
            ], 404); ///404 Not Found
        }
        
        //Для примера.  Но твой вариант будет более уместен, так как будет обвертка для пагинации и прочего. 
        return DishResource::collection($dishes);
    }

    public final function show(int $id): DishResource
    {
        return new DishResource($this->dish->with('dishImages')->find(id: $id));
    }
}
