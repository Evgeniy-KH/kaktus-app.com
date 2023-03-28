<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public final function toArray($request): array
    {
        return [
            'message'=>$this->resource['message'],
            'status' => $this->resource['success'],
            'data' => $this->resource['data'],
        ];
    }
}
