<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
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

    public final function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
