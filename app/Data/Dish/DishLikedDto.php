<?php
declare(strict_types=1);

namespace App\Data\Dish;

use Spatie\LaravelData\Data;

class DishLikedDto extends Data
{

    public function __construct(
        private readonly int $user_id,
        private readonly string $likeable_type,
        private readonly int $likeable_id,
    )
    {
    }

    /**
     * @return int
     */
    public final function getId(): int
    {
        return $this->id;
    }
}
