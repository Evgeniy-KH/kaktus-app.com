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
        if ($this->resource['success'] === false) {
            return [
                'status' => $this->resource['success'],
                'message' => $this->resource['message'],
            ];
        }

        return [
            'status' => $this->resource['success'],
        ];
    }
}
