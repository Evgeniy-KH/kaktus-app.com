<?php
declare(strict_types=1);

namespace App\Service;

use App\Dto\Dish\StoreDto;
use App\Dto\Dish\UpdateDto;
use App\Dto\DishImage\dto;
use App\Models\Dish;
use App\Models\DishImage;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DishService
{
    public function __construct(
        private readonly Dish      $dish,
        private readonly Storage   $storage,
        private readonly DishImage $dishImage,
    )
    {
    }

    // request класс не должен передаваться. Только ДТО или же просто параметры какие то. Массив так же передавать не шикарная идея.
    public final function index(Request $request): LengthAwarePaginator
    {
        //Request не должен передаваться с контроллера в дочерние какие то классы или что то такое. Поэтому наверное придумать какое то ДТО для этого. 
        return $this->dish->filter($request->all())->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(4);
    }

    public final function show(int $id): Dish
    {
        return $this->dish->with('dishImages', 'tags')->find(id: $id);
    }

    public final function store(StoreDto $dto): Dish
    {
        return DB::transaction(function () use ($dto) {
            // Так не пишется!!!!!!!!!!!!

            //$dish =  $this->dish->create([  --- вот так будет правильно. Так как у тебя у же есть экземпляр класса в свойстве класса dish.
            $dish =  $this->dish::create([
                'user_id' => $dto->getUserId(),
                'title' => $dto->getTitle(),
                'ingredients' => $dto->getIngredients(),
                'description' => $dto->getDescription(),
                'price' => $dto->getPrice(),
            ]);  


            //TODO сделанно всё вроде не плохо. Но использовать ENUM  вместо констант. То есть значения у тебя должны быть записаны в ENUM классе, который отвечает за типы картинок для этой моделе. 
            $images  = [
                'main' => $dto->getMainImage(),
                'preview' => $dto->getPreviewImage()
            ];

            foreach ($images as $key => $value) {
                //with DTO
                // КЛАСС пишется с большой буквы!!!!!!!!!!!!! КЛАСС должен называть DTO!!!!!!!!!! а метод если он есть, то dto()
                $image = new dto(
                    dishId: $dish->id,
                    typeId: (int)$this->dishImage::getTypeConst($key),
                    path: (string)$this->storage->disk('public')->put('/images', $value)
                );
                $dishImage = $this->dishImage::create([
                    'dish_id' => $image->getDishId(),
                    'type_id' => $image->getTypeId(),
                    'path' => $image->getPath()
                ]);

                $dish->dishImages()->save($dishImage);
            }

            if ($dto->getTagIds() !== null) {
                $tagIds = $dto->getTagIds();
                $dish->tags()->sync($tagIds);
            }

            return $dish;
        });
    }

    public final function update(UpdateDto $dto, int $id): Dish
    {
        return DB::transaction(function () use ($id, $dto) {
            $dish = $this->show($id);
            $images = array();
            // полный бред! ты что в глаза камни закидываешь, что за обьявление массива?
            //$images = []; ---- вот так должно быть!!!!!!!!!!!!!!!!!

            if ($dto->getTagIds() !== null) {
                $tagIds = $dto->getTagIds();
                $dish->tags()->sync($tagIds);
            }

            if ($dto->getMainImage() !== null) {
                $images['main'] = $dto->getMainImage();
            }
            if ($dto->getPreviewImage() !== null) {
                $images['preview'] = $dto->getPreviewImage();
            }

            foreach ($images as $key => $value) {
                $dishImage = new dto(
                    dishId: $dish->id,
                    typeId: (int)$this->dishImage::getTypeConst($key),
                    path: (string)$this->storage::disk('public')->put('/images', $value)
                );
                $this->updateImage(dto: $dishImage, dish: $dish);
            }

            $dish->fill([
                'title' => $dto->getTitle(),
                'ingredients' => $dto->getIngredients(),
                'description' => $dto->getDescription(),
                'price' => $dto->getPrice()
            ])->save();

            return $dish;
        });
    }

    public final function delete(int $id): bool
    {
        return $this->dish::find($id)->delete();
    }

    public final function updateImage(dto $dto, Dish $dish): bool
    {
        $image = $this->dishImage::getByDishId(dishId: $dto->getDishId())->getByTypeId(typeId: $dto->getTypeId())->first();

        if (Storage::exists($image['path'])) {
            Storage::delete($image['path']);
        }

        return $image->update(['path' => $dto->getPath()]);
    }
}