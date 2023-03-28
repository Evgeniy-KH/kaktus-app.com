<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Filters\Dish\DishFilter;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\BaseController;
use App\Http\Requests\Dish\AddToFavoriteDishRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\FavoriteDishIdResource;
use App\Http\Resources\MessageResource;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FavoriteDishController extends Controller
{
    public function __construct(
        protected UserService $userService, 

        protected FavoriteDish $favoriteDishes
    )
    {
    }
    
    //store
    public final function addToFavoriteDish(AddToFavoriteDishRequest $request): MessageResource|JsonResponse
    {
        //dto  с маленькой буквы. 
        // $dish = $this->favoriteDishes->updateOrCreate(['dish_id' =>  $request->dto()->getId()])
        $dish = auth()->favoriteDishes()->updateOrCreate(['dish_id' => $dishId]);

        
        //TODO почитать хорошие практики как возвращать(ВНИМАЕНИЕ ДЛЯ ТУПЫХ, ТУТ СЛОВО ВОЗВРАЩАТЬ, А НЕ ОБРАБАТЫВАТЬ) ошибки и неудачные ответы
        
        if (!$dish) {
            return (new MessageResource([
                'success' => false,
                'message' => 'Failed to create favorite'
            ]))->response()
                ->setStatusCode(500); //500 Internal Server Error
        } else {
            return new MessageResource([
                "success" => true,
                "data" => $dish,
            ]);
        }
    }

    //delete
    public final function removeFromFavoriteDish(AddToFavoriteDishRequest $request): MessageResource|JsonResponse
    {
        $dishId = $request->DTO()->getId();;
        // $dish= auth()->user()->favoriteDishes()->where('dish_id', $dishId)->delete();
        auth()->favoriteDishes()->findById(dishId: $dishId)->delete();
        
        //TODO  в качесвет примера, метод exist просто для примера, в дб фасаде он как то иначе называется. 
        

        $isSuccess = auth()->favoriteDishes()->findById(dishId: $dishId)->exist();

    
        return new MessageResource([
            "success" => $isSuccess,
            "message" => $isSuccess ? 'yesdasdsadasdassssssssssssssssssssssssssssssssssssss' : 'noccccccccccccccccccccccccccccccccccccccccc'
        ]);
    }

    //show
    public final function getFavoriteDishesId(): AnonymousResourceCollection
    {
        $favoriteDishesId = auth()->user()->favoriteDishes()->get();
        

        return new MessageResource([
            "success" => true,
            "data" => $FavoriteDishIdResource::collection($favoriteDishesId),
        ]);

        // return FavoriteDishIdResource::collection($favoriteDishesId);
    }

    //index
    public final function index(DishFilter $filters): JsonResponse
    {
        return (new MessageResource([
            'success' => true,
            'data' => auth()->user()->favoriteDishes()->with('dish', 'dish.dishImages', 'dish.tags')->paginate(8),
        ]));
    }
}
