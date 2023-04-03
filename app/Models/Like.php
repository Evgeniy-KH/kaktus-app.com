<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $guarded = false;
    protected $table = 'likes';

    protected $fillable = [
        'user_id',
        'likeable_type',
        'likeable_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function likeable()
    {
        return $this->morphTo( 'likeable', 'likeable_type_id');
    }

    public function scopeDishLikes($query)
    {
        return $query->where('likeable_type', '=', 1);
    }

}
