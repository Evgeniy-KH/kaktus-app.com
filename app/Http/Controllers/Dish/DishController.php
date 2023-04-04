<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Http\Resources\DishCollection;
use App\Http\Resources\DishResource;
use App\Http\Resources\ResponseResource;
use App\Service\DishService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function __construct(protected DishService $service)
    {
    }

    public final function index(Request $request): ResponseResource
    {
        //Метод сервсиа лучше наверное назвать как то list или что то такое. Это же не контроллер, что там будет метод index.
        $dishes = $this->service->index(request: $request);
        $isDishEmpty = $dishes->isEmpty();

        return new ResponseResource(
            resource: !$isDishEmpty ? new DishCollection($dishes) : null,
            message: !$isDishEmpty ? '' : 'Your your filter doesn\'t\ match any dishes',
            statusCode: !$isDishEmpty ? 200 : 404
        );
    }

    public final function show(int $id): ResponseResource
    {
        return new ResponseResource(
            resource: new DishResource($this->service->show(id: $id))
        );
    }

    public final function store(StoreRequest $request): ResponseResource
    {

        //DTO должно быть с маленькой буквы. 
        $dish = $this->service->store(dto: $request->DTO());
        // Сверху в методе индекс, у тебя проверка идёт isEmpty  а тут Exists  какая в них разница и почему они используютс? Ну то есть почему в одном случае именно этот метод, а в другом другой. 
        $isExistsDish = $dish->exists();
        

        return new ResponseResource(
            resource: $isExistsDish ? new DishResource($dish) : null,
            message: $isExistsDish ? 'You dish have been successfully stored' : 'Failed to store dish',
            statusCode: $isExistsDish ? 200 : 500
        );
    }

    public final function edit(int $id): ResponseResource
    {
        //Тут должен быть мидлвар перед этим методом, который проверит, если ли вообще такое блюдо для данного пользователя. Может быть он и есть, но проверить что бы он был точно !!!!!!!!!!!!!!!!!
        return new ResponseResource(
            resource: new DishResource($this->service->show(id: $id))
        );
    }

    public final function update(int $id, UpdateRequest $request): ResponseResource
    {
        $dish = $this->service->update(dto: $request->dto(), id: $id);
        $isExistsDish = $dish->exists();

        return new ResponseResource(
            resource: $isExistsDish ? new DishResource($dish) : null,
            message: $isExistsDish ? 'You dish have been successfully updated' : 'Failed to update',
            statusCode: $isExistsDish ? 200 : 500
        );
    }

    public final function delete(int $id): ResponseResource
    {
        $this->service->delete(id: $id);
        
        //ты почти в кажоим мтеоде проверяешь, есть ли переменная или вернее сказать успешный ли результат у нас после выполнения метода сервиса. Но тут ты не выполняешь проверку, а просто веришь в то что удалила.
        // Наверное стоит проверить, не знаб, как лучше. Может быть метод удаления должен возвращать какое то значение тру или фолс взависимости от того удалили или нет. Или просто потом делать запрос в БД, проверять, нет ли такой записи и если нет, то возвращать успешный ответ. 
        //Что вы её удалили.  Ведь даже если мы её не удалили, но её нет в БД, то можно отдать успех.

        return new ResponseResource(
            message: 'You dish have been successfully delete',
        );
    }
}
