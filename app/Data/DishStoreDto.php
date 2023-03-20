<?php
declare(strict_types=1);

namespace App\Data;

use Illuminate\Database\Eloquent\Collection;
use Spatie\LaravelData\Data;

class DishStoreDto extends Data
{

    public function __construct(
        private readonly int        $user_id,
        private readonly string     $title,
        private readonly string     $ingredients,
        private readonly string     $description,
        private readonly string     $price,
        private readonly object     $preview_image,
        private readonly object     $main_image,
        private readonly array|null $tag_ids)
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

    public final function getPrice(): string
    {
        return $this->price;
    }

    public final function getPreviewImage(): object
    {
        return $this->preview_image;
    }

    public final function getMainImage(): object
    {
        return $this->main_image;
    }

    public final function getTagsArray(): array|null
    {
        return $this->tag_ids;
    }

}
