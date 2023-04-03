<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FavoriteDish extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'favorite_dishes';
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
        return $this->belongsToMany(Dish::class, 'favorite_dishes');
    }

    public function scopeFindById($query, $dishId)
    {
        return $query->where('dish_id', "=", $dishId);
    }
}
