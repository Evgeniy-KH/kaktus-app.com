<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Http\Resources\DishResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\TagResource;
use App\Models\Dish;
use App\Models\Tag;
use App\Service\DishService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DishController extends Controller
{
    public function __construct(protected DishService $service, protected  Dish $dish, protected Tag  $tag)
    {
        $this->middleware('auth');
    }

    public final function index(Request $request): AnonymousResourceCollection
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

    public final function store(StoreRequest $request): JsonResponse|MessageResource
    {
        $result = $this->service->store(dto: $request->DTO());

        if ($result) {
            return new MessageResource([
                "success" => true,
                'message' => 'You dish have been successfully stored'
            ]);
        } else {
            return (new MessageResource([
                'success' => false,
                'message' => 'Failed to create favorite'
            ]))->response()
                ->setStatusCode(500);
        }
    }

    public final function edit(int $id): DishResource
    {
        return new DishResource($this->service->getData(id: $id));
    }

    public final function update(int $id, UpdateRequest $request): JsonResponse|MessageResource
    {
        $dishDto = $request->DTO();
        $dish = $this->service->update(dto: $dishDto, id: $id);

        if ($dish) {
            return new MessageResource([
                "success" => true,
                'message' => 'You dish have been successfully stored'
            ]);
        } else {
            return (new MessageResource([
                'success' => false,
                'message' => 'Failed to create favorite'
            ]))->response()
                ->setStatusCode(500);
        }
    }

    public final function delete(int $id): JsonResponse|MessageResource
    {
        $result = $this->service->deleteData(id: $id);

        if ($result) {
            return new MessageResource([
                "success" => true,
                'message' => 'You dish have been successfully delete'
            ]);
        } else {
            return (new MessageResource([
                'success' => false,
                'message' => 'Failed to create favorite'
            ]))->response()
                ->setStatusCode(500);
        }
        // return response()->json(["success" => false]);
    }

    public final function tags(): AnonymousResourceCollection
    {
        $tags = $this->tag->all();

        return TagResource::collection($tags);
    }
}
