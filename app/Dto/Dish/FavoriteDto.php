<?php
declare(strict_types=1);

namespace App\Dto\Dish;

use Spatie\LaravelData\Data;

class FavoriteDto extends Data
{

    public function __construct(
        private readonly int $id,
    )
    {
    }

    public final function getId(): int
    {
        return $this->id;
    }
}
