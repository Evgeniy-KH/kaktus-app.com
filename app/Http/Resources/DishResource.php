<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DishResource extends JsonResource
{
    public final function toArray($request): array
    {
        dd($this);
        if (isset($this['error'])) {
            return [
                'error' => $this['error'],
                'code' => $this['code'],
            ];
        }

        $items = $this->resource->items();

        $collection = collect($items)->map(function ($item)  {

            $dishImages = $item->relationLoaded('dishImages') ? DishImageResource::collection($item->dishImages) : null;
            $dishTags = $item->relationLoaded('tags') ? TagResource::collection($item->tags) : null;
            $dishLikes = $item->relationLoaded('likes') ? LikesResource::collection($item->likes) : null;

            return [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'title' => $item->title,
            'ingredients' => $item->ingredients,
            'description' => $item->description,
            'price' => $item->price,
            'likes_count' => $item->likes_count,
            'dish_images' => $dishImages,
            'tags' => $dishTags,
            'likes' => $dishLikes,
            'created_at' => $item->created_at->toDateTimeString(),
            'updated_at' => $item->updated_at->toDateTimeString(),
        ];
        });

        return [
            'data' =>$collection->toArray(),
            'meta' => [
                'links' => $this->links(),
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'next_page_url' => $this->nextPageUrl(),
                'prev_page_url' => $this->previousPageUrl(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
            ],
        ];


//        return [
//            'id' => $this->id,
//            'user_id' => $this->user_id,
//            'title' => $this->title,
//            'ingredients' => $this->ingredients,
//            'description' => $this->description,
//            'price' => $this->price,
//            'likes_count' => $this->likes_count,
////            'dish_images' => DishImageResource::collection($this->whenLoaded('dishImages')),
////            'tags' => TagResource::collection($this->whenLoaded('tags')),
////            'likes' => LikesResource::collection($this->whenLoaded('likes')),
////            'created_at' => $this->created_at->toDateTimeString(),
////            'updated_at' => $this->updated_at->toDateTimeString(),
//        ];
    }
}
