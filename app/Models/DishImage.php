<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class DishImage extends Model
{
    use HasFactory;
    use SoftDeletes;

    const TYPE_PREVIEW = 0;
    const TYPE_MAIN = 1;

    protected $guarded = false;
    protected $table = 'dish_images';

    protected $fillable = [
        'dish_id',
        'path',
        'type_id'
    ];

    public static function getTypes(): array
    {
        return [
            'preview' => self::TYPE_PREVIEW,
            'main' => self::TYPE_MAIN
        ];
    }

    public static function getTypeConst(string $key): string|null
    {
        $types = self::getTypes();
        return $types[$key] ?? null;
    }

    public function dishes(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dish::class, 'dish_id', 'id');
    }

    public function scopeGetByDishId($query, int $dishId): Builder
    {
        return $query->where('dish_id', '=', $dishId);
    }

    public function scopeGetByTypeId($query, int $typeId): Builder
    {
        return $query->where('type_id', '=', $typeId);
    }
}
