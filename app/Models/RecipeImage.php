<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeImage extends Model
{
    use HasFactory;

    const TYPE_PREVIEW = 0;
    const TYPE_MAIN = 1;

    public static function getTypes(): array
    {
        return [
            self::TYPE_PREVIEW => 'preview',
            self::TYPE_MAIN => 'main'
        ];
    }

    public function recipe(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Recipe::class,'recipe_id', 'id');
    }
}
