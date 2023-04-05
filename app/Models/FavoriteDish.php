<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class FavoriteDish extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'favorite_dish';
    protected $guarded = false;

    protected $fillable = [
        'user_id',
        'dish_id'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dishes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Dish::class, 'favorite_dish');
    }

    public function scopeFindById($query, $dishId): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('dish_id', "=", $dishId);
    }
}
