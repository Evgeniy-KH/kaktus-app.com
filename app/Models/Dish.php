<?php

namespace App\Models;

use App\Contracts\Likeable;
use App\Models\Concerns\Likes;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dish extends Model implements Likeable
{

    use HasFactory;
    use SoftDeletes;
    use Likes;
    use Filterable;

    protected $table = 'dishes';
    protected $guarded = false;

    public final function dishImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DishImage::class, 'dish_id', 'id');
    }

    public final function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'dishes_tags', 'dish_id', 'tag_id');
    }

    public final function favorites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FavoriteDish::class,'dish_id','id' );
    }

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($fileToDelete) {
            $fileToDelete->dishImages()->delete();
            $fileToDelete->tags()->detach();
            $fileToDelete->favorites()->delete();
        });
    }
}
