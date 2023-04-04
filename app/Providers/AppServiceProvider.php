<?php

namespace App\Providers;

use App\Models\Dish;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Relation::morphMap([
            1 => Dish::class,
        ]);
    }
}
