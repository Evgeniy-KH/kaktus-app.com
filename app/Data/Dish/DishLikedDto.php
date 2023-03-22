<?php
declare(strict_types=1);

namespace App\Data\Dish;

use Spatie\LaravelData\Data;

class DishLikedDto extends Data
{

    public function __construct(
        private readonly int $id,
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
