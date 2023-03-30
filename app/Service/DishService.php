<?php
declare(strict_types=1);

namespace App\Service;

use App\Dto\Dish\StoreDto;
use App\Dto\Dish\UpdateDto;
use App\Models\Dish;
use App\Models\DishImage;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DishService
{

    //TODo почиитать внедрение зависимостей Laravel;!!!!!!!!!!!!!!
    //поменять все модели.на иньекции.
    public function _construct(private Dish $dish) 
    {

    }

    //TODO. Внёс измененияч которые ломают код.

    public function index(Request $request): LengthAwarePaginator
    {
        return $this->dish->filter($request->all())->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(4);
    }

    public final function show(int $id): Dish
    {
        return $this->dish->with('dishImages', 'tags')->find(id: $id);
    }

    public function store(StoreDto $dto): Dish
    {
        return DB::transaction(function () use ($dto) {
            //TODO через цикл.

            $previewImage['image'] = Storage::disk('public')->put('/images', $dto->getPreviewImage());
            $previewImage['type_id'] = DishImage::TYPE_PREVIEW;

            $mainImage['image'] = $data['main_image'] = Storage::disk('public')->put('/images', $dto->getMainImage());
            $mainImage['type_id'] = DishImage::TYPE_MAIN;

            $allImages = [$previewImage, $mainImage];

            $dish = Dish::create([
                'user_id' => $dto->getUserId(),
                'title' => $dto->getTitle(),
                'ingredients' => $dto->getIngredients(),
                'description' => $dto->getDescription(),
                'price' => $dto->getPrice(),
            ]);

            foreach ($allImages as $image) {
                $image['dish_id'] = $dish->id;
                // firstOrCreate почему тут опять этот гдскитй firstOrCreate. Почему это тут может быть ? ведь мы только создаём блюдо. 
                $image = DishImage::firstOrCreate($image);
                $dish->dishImages()->save($image);
            }

            return $dish;
        });
    }

    public function update(UpdateDto $dto, int $id): Dish
    {
        return DB::transaction(function () use ($id, $dto) {
            $dish = $this->getData($id);
            
            //TODO переименовать метод. 
            //getTagsArray ?????? // getTagIds
            if ($dto->getTagsArray() !== null) {
                $tagIds = $dto->getTagsArray();
                $dish->tags()->sync($tagIds);
            }
            

            //TODO задача со звёздочкой, ну звездачка для тупых. Сделать это в цикле.!!!
            if ($dto->getPreviewImage() !== null) {
                $previewImage['image'] = Storage::disk('public')->put('/images', $dto->getPreviewImage());
                $previewImage['type_id'] = DishImage::TYPE_PREVIEW;
                $previewImage['dish_id'] = $dish->id;
                //никакого array !!!!!!!!!!!!!!!!!никакого array. ТУТ DTO
                $updatedImage = $this->updateImage(image: $previewImage, dish: $dish);
            }

            if ($dto->getMainImage() !== null) {
                $mainImage['image'] = Storage::disk('public')->put('/images', $dto->getMainImage());
                $mainImage['type_id'] = DishImage::TYPE_MAIN;
                $mainImage['dish_id'] = $dish->id;
                $updatedImage = $this->updateImage(image: $mainImage, dish: $dish);
            }
            

            //TODO подлный бред. Не нужно обновлять данные, которые не пришки с обновления.
            //1. У тебя все данные обязательные
            //2. 


            // 

            $dish->update([
                //TODO обновлять только то, что пришло. 
                // 'title' => $dto->getTitle() ?? $dish->title,
                // 'ingredients' => $dto->getIngredients() ?? $dish->ingredients,
                // 'description' => $dto->getDescription() ?? $dish->description,
                // 'price' => $dto->getPrice() ?? $dish->price
            ]);

            return $dish;
        });
    }

    public function getData(int $id): Dish
    {
        return Dish::with('dishImages', 'tags')->find($id);
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            // удалить одним запросом!!!!!
            // И еща прочитать как это удалять через события в модели. ТО есть если мы удаляем запись в моделе, то должны удалить и то, что укажем ещё в событие. Или же 7не удалить, а что то сделаем. Одним словом, почитать про события модели в laravel.
            auth()->user()->favoriteDishes()->getByDishId(dish_id: $id)->delete();
            Dish::find($id)->delete();

            return true;
        });
    }

    public function updateImage(array $image, Dish $dish): DishImage
    {   
        //TODO поменять на ОБНОВЛДЕНИЕ, КАРТИНКА ОБНОВЛЯЕТСЯ. ЗНАЧИТ тут нужно обновить!
        // DishImage::findIdAndType($image['dish_id'], $image['type_id'])->delete();
        // DishImage::getByDishId(dishId: $image['dish_id'])->getByTуpeId(typeId: $image['type_id'])->delete();

        $newImage = DishImage::firstOrCreate($image);
        //TODO подсказка для ненормальных, обновить именно тут. 
        return $dish->dishImages()->save($newImage);
    }
}
