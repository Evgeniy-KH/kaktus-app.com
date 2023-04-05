<?php
declare(strict_types=1);

namespace App\Dto\DishImage;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class DTO extends Data
{

    public function __construct(
        private readonly int          $dishId,
        private readonly int          $typeId,
        private readonly string       $path,
    )
    {
    }

    public final function getDishId(): int
    {
        return $this->dishId;
    }

    public final function getTypeId(): int
    {
        return $this->typeId;
    }

    public final function getPath(): string
    {
        return $this->path;
    }
}
