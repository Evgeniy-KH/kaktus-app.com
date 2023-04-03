<?php
///почему название директории Concerns and not a trait
namespace App\Models\Concerns;

use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Likes
{
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable', 'likeable_type_id');
    }
}
