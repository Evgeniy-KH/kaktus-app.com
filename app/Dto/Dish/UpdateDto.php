<?php
declare(strict_types=1);

namespace App\Dto\Dish;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class UpdateDto extends Data
{
    public function __construct(
        private readonly int               $id,
        private readonly int               $userId,
        private readonly string|null       $title,
        private readonly string|null       $ingredients,
        private readonly string|null       $description,
        private readonly float|null        $price,
        private readonly UploadedFile|null $previewImage,
        private readonly UploadedFile|null $mainImage,
        private readonly array|null        $tagIds)
    {
    }

    public final function getId(): int
    {
        return $this->id;
    }

    public final function getUserId(): int
    {
        return $this->userId;
    }

    public final function getTitle(): string|null
    {
        return $this->title;
    }

    public final function getIngredients(): string|null
    {
        return $this->ingredients;
    }

    public final function getDescription(): string|null
    {
        return $this->description;
    }

    public final function getPrice(): float|null
    {
        return $this->price;
    }

    public final function getPreviewImage(): UploadedFile|null
    {
        return $this->previewImage;
    }

    public final function getMainImage(): UploadedFile|null
    {
        return $this->mainImage;
    }

    public final function getTagsArray(): array|null
    {
        return $this->tagIds;
    }
}
