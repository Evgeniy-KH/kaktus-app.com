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
use App\Models\User;
use App\Service\UserService;
use Illuminate\Http\Request;

class FavoriteDishController extends Controller
{
    public function __construct(
        protected readonly Dish         $dish,
        protected readonly User         $user,
    )
    {
    }

    public final function index(int $userId): ResponseResource
    {
        $user = $this->user->find($userId);
        //Использоватье SCOPE.
        $dishes = $this->dish->with('dishImages', 'tags')
            ->whereHas('favorites', function ($query) use ($userId) {
                $query->where('favorite_dish.user_id', '=', $userId);
            })
            ->paginate(8);

        return new ResponseResource(
            resource: new DishCollection($dishes),
            message: !$dishes->isEmpty() ? '' : 'Your list of favorites dishes are empty',
        );
    }

    public final function store(AddToFavoriteDishRequest $request): ResponseResource
    {
        $dish = $this->user->find($request->dto()->getUserId())->favoriteDishes()->updateOrCreate(['dish_id' => $request->dto()->getDishId()]);

        return new ResponseResource(
            resource: !$dish ? '' : $dish,
            message: !$dish ? 'Failed to create favorite' : 'Store successfully',
            statusCode: !$dish ? 500 : 200
        );
    }

    public final function delete(int $userId, int $dishId): ResponseResource
    {
        $user = $this->user->find($userId);
        //Мы же уже говорили, и можно прсомотреть в документации и ларавель и вообще rest api. Что при удалении ИД передается в качестве параметра урла, а не в теле запроса.
        $user->favoriteDishes()->findById(dishId: $dishId)->delete(); // - посмотри что она возвращает и в каких ситуациях.!!!!!!!! Потому что она возвраещает количество удаленных строк. 

        $isSuccess = $user->favoriteDishes()->findById(dishId: $dishId)->doesntExist();

        return new ResponseResource(
            message: $isSuccess ? 'Deleted successfully' : 'Try again later',
            statusCode: $isSuccess ? 200 : 404
        );
    }

    public final function show(int $userId): ResponseResource
    {
        // В целом по уму, тут было бы праивльно, что бы ты передавала параметр user id  для того что бы показать  блюда. Ведь ты показываешь любимые блюда пользователя. И параметр который характеризует именно то, что нам нужно, является userid
        // Я понимаюб, что ты только покзаываешь для "себя" эти бблюда. Но тем не менее, канонично и правильно со стороны разработки было бы делать, согласно КРУД. А круд требует в этом запросе параметр. Этот параметр юзер ид.
        // Ну и само собой, что ыт должно проверять, имеет ли право этот пользователь, смотреть за этим блюдом.
        $favoriteDishesId = $this->user->find($userId)->favoriteDishes()->get();

        return new ResponseResource(
            resource: FavoriteDishIdResource::collection($favoriteDishesId),
        );
    }
}
