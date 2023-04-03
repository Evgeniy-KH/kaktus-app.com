<?php
// ПОчему папка дата???? дата означает данные а тут у тебя не данные тут у тебя классы которые отвечают. Папка Dto
declare(strict_types=1);

namespace App\Dto\Dish;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class StoreDto extends Data
{
    public function __construct(
        private readonly int          $userId,
        private readonly string       $title,
        private readonly string       $ingredients,
        private readonly string       $description,
        private readonly float        $price,
        private readonly UploadedFile $previewImage,
        private readonly UploadedFile $mainImage,
        private readonly array|null   $tagIds)
    {
    }

    public final function getUserId(): int
    {
        return $this->userId;
    }

    public final function getTitle(): string
    {
        return $this->title;
    }

    public final function getIngredients(): string
    {
        return $this->ingredients;
    }

    public final function getDescription(): string
    {
        return $this->description;
    }

    public final function getPrice(): float
    {
        return $this->price;
    }

    public final function getPreviewImage(): UploadedFile
    {
        return $this->previewImage;
    }

    public final function getMainImage(): UploadedFile
    {
        return $this->mainImage;
    }

    public final function getTagIds(): array|null
    {
        return $this->tagIds;
    }
}
