<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public final function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
