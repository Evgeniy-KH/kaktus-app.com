<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LikesResource extends  JsonResource
{
    public final function toArray($request):array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'likeable_type' => $this->likeable_type,
            'likeable_id' => $this->likeable_id,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
