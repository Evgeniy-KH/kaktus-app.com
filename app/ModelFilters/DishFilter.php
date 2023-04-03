<?php
declare(strict_types=1);
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;


class DishFilter extends ModelFilter
{
    public $relations = [];

    public final function keyword(string $keyword): self
    {
        return $this->where(function ($query) use ($keyword) {
            return $query->where('title', 'LIKE', "%$keyword%")
                ->orWhere('description', 'LIKE', "%$keyword%");
        });
    }

    public final function price(string $price): self
    {
        list($min, $max) = explode(",", $price);

        return $this->where('price', '>=', $min)
            ->where('price', '<=', $max)
            ->with('dishImages', 'tags');
    }

    public final function tagsId(string $tagsId): self
    {
        $filter = function ($query) use ($tagsId) {
            $query->where('tag_id', $tagsId);
        };

        return $this->whereHas('tags', $filter);
    }

    public function userId(int $userId)
    {
        return $this->where('user_id', $userId);
    }
}
