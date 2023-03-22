<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DishCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    /**
     * The resource that this resource collects.
     *
     * @
     * var string
     */
    public $collects = DishResource::class;

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
