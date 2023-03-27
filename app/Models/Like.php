<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function likeable()
    {
        return $this->morphTo();
    }

    public function scopeDishLikes($query)
    {
        return $query->where('likeable_type', '=', 'App\\Models\\Dish');
    }

}
