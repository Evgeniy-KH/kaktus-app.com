<?php

namespace App\Providers;

use App\Enums\DishImagesType;
use App\Models\Dish;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(DishImagesType::class, function ($app) {
            return new DishImagesType(DishImagesType::Preview);
        });
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Relation::morphMap([
            1 => Dish::class,
        ]);
    }
}
