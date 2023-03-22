<?php
declare(strict_types=1);

namespace App\Service;

use App\Models\Dish;
use App\Models\DishImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DishService
{
    public function store(object $dishDto): Dish
    {
        return DB::transaction(function () use ($dishDto) {
            $previewImage['image'] = Storage::disk('public')->put('/images', $dishDto->getPreviewImage());
            $previewImage['type_id'] = DishImage::TYPE_PREVIEW;

            $mainImage['image'] = $data['main_image'] = Storage::disk('public')->put('/images', $dishDto->getMainImage());
            $mainImage['type_id'] = DishImage::TYPE_MAIN;

            $allImages = [$previewImage, $mainImage];

            $dish = Dish::firstOrCreate([
                'user_id' => $dishDto->getUserId(),
                'title' => $dishDto->getTitle(),
                'ingredients' => $dishDto->getIngredients(),
                'description' => $dishDto->getDescription(),
                'price' => $dishDto->getPrice(),
            ]);

            foreach ($allImages as $image) {
                $image['dish_id'] = $dish->id;
                $image = DishImage::firstOrCreate($image);
                $dish->dishImages()->save($image);
            }

            return $dish;
        });

    }

    //
    public function update(object $dishDto, int $id): Dish
    {
        return DB::transaction(function () use ($id, $dishDto) {
            $dish = $this->getData($id);

            if ($dishDto->getTagsArray() !== null) {
                $tagIds = $dishDto->getTagsArray();
                $dish->tags()->sync($tagIds);
            }

            if ($dishDto->getPreviewImage() !== null) {
                $previewImage['image'] = Storage::disk('public')->put('/images', $dishDto->getPreviewImage());
                $previewImage['type_id'] = DishImage::TYPE_PREVIEW;
                $previewImage['dish_id'] = $dish->id;
                $updatedImage = $this->updateImage($previewImage, $dish);
            }

            if ($dishDto->getMainImage() !== null) {
                $mainImage['image'] = Storage::disk('public')->put('/images', $dishDto->getMainImage());
                $mainImage['type_id'] = DishImage::TYPE_MAIN;
                $mainImage['dish_id'] = $dish->id;
                $updatedImage = $this->updateImage($mainImage, $dish);
            }

            $dish->update([
                'title' => $dishDto->getTitle() ?? $dish->title,
                'ingredients' => $dishDto->getIngredients() ?? $dish->ingredients,
                'description' => $dishDto->getDescription() ?? $dish->description,
                'price' => $dishDto->getPrice() ?? $dish->price
            ]);

//            if (isset($tagIds)) {
//                $dish->tags()->sync($tagIds);
//            }

//            if (isset($previewImage, $mainImage)) {
//                $allImages = [$previewImage, $mainImage];
//                foreach ($allImages as $image) {
//                    $image['dish_id'] = $dish->id;
//                    $images = DishImage::where('dish_id', $image['dish_id'])->delete();;
//                    $image = DishImage::firstOrCreate($image);
//                    $dish->dishImages()->save($image);
//                }
//
//            } else if (isset($previewImage)) {
//                $previewImage['dish_id'] = $dish->id;
//                //WHERE по проекту нигде не должны быть. ТОлько использования SCOPE!!!!!!!!!!!!Прочитать scope.
//                $images = DishImage::where('dish_id', $previewImage['dish_id'])->where('type_id', DishImage::TYPE_PREVIEW)->delete();
//                $previewImage = DishImage::firstOrCreate($previewImage);
//                $dish->dishImages()->save($previewImage);
//            } else if (isset($mainImage)) {
//                $mainImage['dish_id'] = $dish->id;
//                $images = DishImage::where('dish_id', $mainImage['dish_id'])->where('type_id', DishImage::TYPE_MAIN)->delete();
//                $mainImage = DishImage::firstOrCreate($mainImage);
//                $dish->dishImages()->save($mainImage);
//            }
//
//            if (isset($allImages)) {
//                foreach ($allImages as $image) {
//                    $image['dish_id'] = $dish->id;
//                    $image = DishImage::firstOrCreate($image);
//                    $dish->getDishImages()->save($image);
//                }
//            }

            return $dish;
        });
    }


    public function getData(int $id): Dish
    {
        return Dish::where('id', $id)->with('dishImages', 'tags')->first();
    }

    public function deleteData(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            auth()->user()->favoriteDishes()->where('dish_id', $id)->delete();
            Dish::findOrFail($id)->delete();

            return true;
        });
    }

    public function updateImage(array $image, Dish $dish): DishImage
    {
        $currentImages = DishImage::findIdAndType($image['dish_id'], $image['type_id'])->delete();
        $newImage = DishImage::firstOrCreate($image);
        return $dish->dishImages()->save($newImage);
    }

}
