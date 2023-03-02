<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishImage extends Model
{
    use HasFactory;

    const TYPE_PREVIEW = 0;
    const TYPE_MAIN = 1;

    protected $guarded = false;
    protected $table = 'dish_images';


    public static function getTypes(): array
    {
        return [
            self::TYPE_PREVIEW => 'preview',
            self::TYPE_MAIN => 'main'
        ];
    }

    public function dishes(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dish::class,'dish_id', 'id');
    }
}
