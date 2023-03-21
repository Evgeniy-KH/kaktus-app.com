<?php
declare(strict_types=1);

namespace App\Data;

use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Data;

class DishUpdateDto extends Data
{

    public function __construct(
        private readonly int         $id,
        private readonly int         $user_id,
        private readonly string|null $title,
        private readonly string|null $ingredients,
        private readonly string|null $description,
        private readonly string|null $price,
        private readonly object|null $preview_image,
        private readonly object|null $main_image,
        private readonly array|null  $tag_ids)
    {
    }

    /**
     * @return int
     */
    public final function getId(): int
    {
        return $this->id;
    }

    public final function getUserId(): int
    {
        return $this->user_id;
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

    public final function getPrice(): string|null
    {
        return $this->price;
    }

    public final function getPreviewImage(): object|null
    {
        return $this->preview_image;
    }

    public final function getMainImage(): object|null
    {
        return $this->main_image;
    }

    public final function getTagsArray(): array|null
    {
        return $this->tag_ids;
    }

}
