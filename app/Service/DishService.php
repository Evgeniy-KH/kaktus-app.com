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

    public final function index(Request $request): LengthAwarePaginator
    {
        return $this->dish->filter($request->all())->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(4);
    }

    public final function show(int $id): Dish
    {
        return $this->dish->with('dishImages', 'tags')->find(id: $id);
    }

    public final function store(StoreDto $dto): Dish
    {
        return DB::transaction(function () use ($dto) {
            //TODO через цикл.

            $previewImage['path'] = $this->storage::disk('public')->put('/images', $dto->getPreviewImage());
            $previewImage['type_id'] = $this->dishImage::TYPE_PREVIEW;

            $mainImage['path'] = $this->storage::disk('public')->put('/images', $dto->getMainImage());
            $mainImage['type_id'] = $this->dishImage::TYPE_MAIN;

            $allImages = [$previewImage, $mainImage];

            $dish = Dish::create([
                'user_id' => $dto->getUserId(),
                'title' => $dto->getTitle(),
                'ingredients' => $dto->getIngredients(),
                'description' => $dto->getDescription(),
                'price' => $dto->getPrice(),
            ]);
            // переделать на креат а не апдейт
//            if ($dto->getImages() !== null) {
//                foreach ($dto->getImages() as $key => $value) {
//                    $dishImage = new dto(
//                        dishId: $dish->id,
//                        typeId: (int)$this->dishImage::getTypeConst($key),
//                        path: (string)$this->storage::disk('public')->put('/images', $value)
//                    );
//                    $this->updateImage(dto: $dishImage, dish: $dish);
//                }
//            }

            if ($dto->getTagIds() !== null) {
                $tagIds = $dto->getTagIds();
                $dish->tags()->sync($tagIds);
            }

            foreach ($allImages as $image) {
                $image['dish_id'] = $dish->id;
                // firstOrCreate почему тут опять этот гдскитй firstOrCreate. Почему это тут может быть ? ведь мы только создаём блюдо.
                $image = $this->dishImage::create($image);
                $dish->dishImages()->save($image);
            }

            return $dish;
        });
    }

    public final function update(UpdateDto $dto, int $id): Dish
    {
        return DB::transaction(function () use ($id, $dto) {
            $dish = $this->show($id);

            if ($dto->getTagIds() !== null) {
                $tagIds = $dto->getTagIds();
                $dish->tags()->sync($tagIds);
            }

            if ($dto->getImages() !== null) {
                foreach ($dto->getImages() as $key => $value) {
                    $dishImage = new dto(
                        dishId: $dish->id,
                        typeId: (int)$this->dishImage::getTypeConst($key),
                        path: (string)$this->storage::disk('public')->put('/images', $value)
                    );
                    $this->updateImage(dto: $dishImage, dish: $dish);
                }
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
        return Dish::find($id)->delete();
    }

    public final function updateImage(dto $dto, Dish $dish): bool
    {
        // $images = DishImage::getByDishId(dishId: $dto->getDishId())->getByTypeId(typeId: $dto->getTypeId())->update('image'=>$dto->getPath());
        $image = DishImage::getByDishId(dishId: $dto->getDishId())->getByTypeId(typeId: $dto->getTypeId())->first();

        if (Storage::exists($image['path'])) {
            Storage::delete($image['path']);
        }

        return $image->update(['path' => $dto->getPath()]);
    }
}
// DishImage::getByDishId(dishId: $image['dish_id'])->getByTуpeId(typeId: $image['type_id'])->delete();

//            if ($dto->getPreviewImage() !== null) {
//                $previewImage['image'] = $this->storage::disk('public')->put('/images', $dto->getPreviewImage());
//                $previewImage['type_id'] = $this->dishImage::TYPE_PREVIEW;
//                $previewImage['dish_id'] = $dish->id;
//                //никакого array !!!!!!!!!!!!!!!!!никакого array. ТУТ DTO
//                $updatedImage = $this->updateImage(image: $previewImage, dish: $dish);
//            }
//
//            if ($dto->getMainImage() !== null) {
//                $mainImage['image'] = $this->storage::disk('public')->put('/images', $dto->getMainImage());
//                $mainImage['type_id'] = $this->dishImage::TYPE_MAIN;
//                $mainImage['dish_id'] = $dish->id;
//                $updatedImage = $this->updateImage(image: $mainImage, dish: $dish);
//            }
