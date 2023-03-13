<?php

namespace App\Models;

use App\Contracts\Likeable;
use App\Models\Concerns\Likes;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dish extends Model  implements Likeable
{
    use HasFactory;
    use SoftDeletes;
    use Likes;
    use Filterable;

    protected $table = 'dishes';
    protected $guarded = false;

    public function dishImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DishImage::class, 'dish_id','id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class,'dishes_tags', 'dish_id', 'tag_id');
    }

//    public function scopeFilter($query, $filters)
//    {
//        return $filters->apply($query);
//    }

    public function scopeHasRevenue($query)
    {
        return $query->where('total_revenue', '>', 0);
    }

    public function favorites()
    {
        return $this->belongsToMany(FavoriteDish::class);
    }


}
