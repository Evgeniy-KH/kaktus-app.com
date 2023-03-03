<?php

namespace App\Models\Concerns;

use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;

trait Likes
{
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
