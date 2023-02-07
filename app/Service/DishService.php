<?php

namespace App\Service;

use App\Models\Dish;
use App\Models\DishImage;
use Dflydev\DotAccessData\Data;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DishService
{
    public function store($data)
    {
        try {
            Db::beginTransaction();

            if (isset($data['tag_ids'])) {
                $tagIds = $data['tag_ids'];
                unset($data['tag_ids']);
            }

            $previewImage['image'] = $data['preview_image'] = Storage::disk('public')->put('/images', $data['preview_image']);
            $previewImage['type_id'] = DishImage::TYPE_PREVIEW;

            unset($data['preview_image']);

            $mainImage['image'] = $data['main_image'] = Storage::disk('public')->put('/images', $data['main_image']);
            $mainImage['type_id'] = DishImage::TYPE_MAIN;

            unset($data['main_image']);

            $allImages = [$previewImage, $mainImage];
            $dish = Dish::firstOrCreate($data);

            if (isset($tagIds)) {
                $dish->tags()->attach($tagIds);
            }

            foreach ($allImages as $image) {
                $image['dish_id'] = $dish->id;
                $image = DishImage::firstOrCreate($image);
                $dish->getDishImages()->save($image);
            }

            Db::commit();

        } catch (Exception $exception) {
            dd($exception->getMessage());
            Db::rollBack();
            abort(500);
        }
    }

    public function update($data, $dish)
    {
        try {
            Db::beginTransaction();

            if (isset($data['tag_ids'])) {
                $tagIds = $data['tag_ids'];
                unset($data['tag_ids']);
            }

            if (isset($data['preview_image'])) {
                $previewImage['image'] = $data['preview_image'] = Storage::disk('public')->put('/images', $data['preview_image']);
                $previewImage['type_id'] = DishImage::TYPE_PREVIEW;
                unset($data['preview_image']);
            }

            if (isset($data['main_image'])) {
                $mainImage['image'] = $data['main_image'] = Storage::disk('public')->put('/images', $data['main_image']);
                $mainImage['type_id'] = DishImage::TYPE_MAIN;
                unset($data['main_image']);
            }

            $dish->update($data);

            if (isset($tagIds)) {
                $dish->tags()->sync($tagIds);
            }

            if (isset($previewImage, $mainImage)) {
                $allImages = [$previewImage, $mainImage];

                foreach ($allImages as $image) {
                    $image['dish_id'] = $dish->id;
                    $images = DishImage::where('dish_id', $image['dish_id'])->delete();;
                    $image = DishImage::firstOrCreate($image);
                    $dish->getDishImages()->save($image);
                }
            } else if (isset($previewImage)) {
                $previewImage['dish_id'] = $dish->id;
                $images = DishImage::where('dish_id', $previewImage['dish_id'])->where('type_id', DishImage::TYPE_PREVIEW)->delete();
                $previewImage = DishImage::firstOrCreate($previewImage);
                $dish->getDishImages()->save($previewImage);
            } else if (isset($mainImage)) {
                $mainImage['dish_id'] = $dish->id;
                $images = DishImage::where('dish_id', $mainImage['dish_id'])->where('type_id', DishImage::TYPE_MAIN)->delete();
                $mainImage = DishImage::firstOrCreate($mainImage);
                $dish->getDishImages()->save($mainImage);
            }

            if (isset($allImages)) {
                foreach ($allImages as $image) {
                    $image['dish_id'] = $dish->id;
                    $image = DishImage::firstOrCreate($image);
                    $dish->getDishImages()->save($image);
                }
            }

            Db::commit();
        } catch (Exception $exception) {
            dd($exception->getMessage());
            Db::rollBack();
            abort(500);
        }

        return $dish;
    }

}
