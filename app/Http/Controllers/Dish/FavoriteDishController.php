<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Filters\Dish\DishFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\AddToFavoriteDishRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\FavoriteDishIdResource;
use App\Http\Resources\ResponseResource;
use App\Models\Dish;
use App\Models\FavoriteDish;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;

class FavoriteDishController extends Controller
{
    public function __construct(
        protected readonly UserService  $userService,
        protected readonly FavoriteDish $favoriteDishes,
        protected readonly Dish $dish
    )
    {
    }

    public final function store(AddToFavoriteDishRequest $request): ResponseResource
    {
        $dish =auth()->user()->favoriteDishes()->updateOrCreate(['dish_id' => $request->dto()->getId()]);

        return new ResponseResource(
            resource: !$dish  ? '' : $dish,
            message:  !$dish  ? 'Failed to create favorite' : 'Store successfully',
            statusCode:!$dish  ?  500 : 200
        );
    }

    public final function delete(AddToFavoriteDishRequest $request): ResponseResource
    {
        //Мы же уже говорили, и можно прсомотреть в документации и ларавель и вообще rest api. Что при удалении ИД передается в качестве параметра урла, а не в теле запроса.
        $dishId = $request->dto()->getId();;
        auth()->user()->favoriteDishes()->findById(dishId: $dishId)->delete();
        $isSuccess = auth()->user()->favoriteDishes()->findById(dishId: $dishId)->doesntExist();

        return new ResponseResource(
            message: $isSuccess ? 'Deleted successfully' : 'Try again later',
            statusCode: $isSuccess ? 200 : 404
        );
    }

    public final function show(): ResponseResource
    {
        // В целом по уму, тут было бы праивльно, что бы ты передавала параметр user id  для того что бы показать  блюда. Ведь ты показываешь любимые блюда пользователя. И параметр который характеризует именно то, что нам нужно, является userid
        // Я понимаюб, что ты только покзаываешь для "себя" эти бблюда. Но тем не менее, канонично и правильно со стороны разработки было бы делать, согласно КРУД. А круд требует в этом запросе параметр. Этот параметр юзер ид.
        // Ну и само собой, что ыт должно проверять, имеет ли право этот пользователь, смотреть за этим блюдом.
        $favoriteDishesId = auth()->user()->favoriteDishes()->get();

        return new ResponseResource(
            resource: FavoriteDishIdResource::collection($favoriteDishesId),
        );
    }

    public final function index(DishFilter $filters): ResponseResource
    {
        //ИЛИ JOIN или WITH .  подсказка для даунов, что есть ещё такой метод как whereHas и в документации он описан. 
        
        $dishes = $this->dish::select('dishes.*')
            ->join('favorite_dishes', 'favorite_dishes.dish_id', '=', 'dishes.id')
            ->where('favorite_dishes.user_id', auth()->user()->id)
            ->with('dishImages', 'tags')
            ->paginate(8);

        return new ResponseResource(
            resource: !$dishes->isEmpty() ? new DishCollection($dishes) : '',
            message:   !$dishes->isEmpty() ? '' : 'Your list of favorites dishes are empty',
        );
    }
}
