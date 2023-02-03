<?php

namespace App\Service;

use App\Models\Dish;
use App\Models\DishImage;
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

//    public function update($data, $post)
//    {
//        try {
//            Db::beginTransaction();
//
//            if (isset($data['tag_ids'])) {
//                $tagIds = $data['tag_ids'];
//                unset($data['tag_ids']);
//            }
//            if (isset($data['preview_image'])) {
//                $data['preview_image'] = Storage::disk('public')->put('/images', $data['preview_image']);
//            }
//
//            if (isset($data['main_image'])) {
//                $data['main_image'] = Storage::disk('public')->put('/images', $data['main_image']);
//            }
//
//            $post->update($data);
//            if (isset($tagIds)) {
//                $post->tags()->sync($tagIds);
//            }
//
//            Db::commit();
//        } catch (Exception $exception) {
//            Db::rollBack();
//            abort(500);
//        }
//
//        return $post;
//    }

}
