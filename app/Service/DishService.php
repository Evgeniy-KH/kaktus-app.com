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
    //TODO. Внёс измененияч которые ломают код.

    public function index(Request $request): LengthAwarePaginator
    {
        return Dish::filter($request->all())->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(4);
    }

    public final function show(int $id): Dish
    {
        return Dish::with('dishImages', 'tags')->find(id: $id);
    }

    public function store(StoreDto $dto): Dish
    {
        return DB::transaction(function () use ($dto) {
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

            if ($dto->getTagsArray() !== null) {
                $tagIds = $dto->getTagsArray();
                $dish->tags()->sync($tagIds);
            }

            if ($dto->getPreviewImage() !== null) {
                $previewImage['image'] = Storage::disk('public')->put('/images', $dto->getPreviewImage());
                $previewImage['type_id'] = DishImage::TYPE_PREVIEW;
                $previewImage['dish_id'] = $dish->id;
                $updatedImage = $this->updateImage(image: $previewImage, dish: $dish);
            }

            if ($dto->getMainImage() !== null) {
                $mainImage['image'] = Storage::disk('public')->put('/images', $dto->getMainImage());
                $mainImage['type_id'] = DishImage::TYPE_MAIN;
                $mainImage['dish_id'] = $dish->id;
                $updatedImage = $this->updateImage(image: $mainImage, dish: $dish);
            }

            $dish->update([
                'title' => $dto->getTitle() ?? $dish->title,
                'ingredients' => $dto->getIngredients() ?? $dish->ingredients,
                'description' => $dto->getDescription() ?? $dish->description,
                'price' => $dto->getPrice() ?? $dish->price
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
            auth()->user()->favoriteDishes()->where('dish_id', $id)->delete();
            Dish::find($id)->delete();

            return true;
        });
    }

    public function updateImage(array $image, Dish $dish): DishImage
    {
        DishImage::findIdAndType($image['dish_id'], $image['type_id'])->delete();
        $newImage = DishImage::firstOrCreate($image);

        return $dish->dishImages()->save($newImage);
    }
}
