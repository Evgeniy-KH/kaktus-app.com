<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo( 'likeable', 'likeable_type_id');
    }

    public function scopeDishLikes($query): Builder
    {
        return $query->where('likeable_type', '=', 1);
    }

}
