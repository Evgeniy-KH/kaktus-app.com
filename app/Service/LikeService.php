<?php
declare(strict_types=1);

namespace App\Service;

use App\Contracts\Likeable;
use App\Models\Dish;
use App\Models\User;

class LikeService
{
    public function __construct(
        private readonly User $user,
        private readonly Dish $dish,
    )
    {
    }

    public final function store(Likeable $likeable): User
    {
        return auth()->user()->like($likeable);
    }

    public final function delete(int $id, Likeable $likeable): User
    {
        return $this->user->find($id)->unlike($likeable);
    }

    public final function showUsers(array $ids): \Illuminate\Database\Eloquent\Collection
    {
        return $this->user->find(id: $ids)->take(4);
    }

    public final function showDishes(int $id): \Illuminate\Database\Eloquent\Collection
    {
        return $this->dish->with('dishImages', 'tags')
            ->whereHas('likes', function ($query) use ($id) {
                $query->where('likes.likeable_type_id', '=', 1)
                    ->where('likes.user_id', '=', $id);
            })
            ->get();
    }
}

