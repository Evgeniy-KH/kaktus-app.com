<?php

namespace App\Providers;

use App\View\Composer\DishCreateComposer;
use App\View\Composer\DishEditComposer;
use App\View\Composer\DishShowComposer;
use App\View\Composer\FavoritesDishesComposer;
use App\View\Composer\TagComposer;
use App\View\Composer\UserComposer;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\View;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('user.edit', UserComposer::class);
        View::composer('layouts.app', UserComposer::class);
        View::composer('dish.edit', DishEditComposer::class);
        View::composer('dish.create', DishCreateComposer::class);
        View::composer('dish.show', DishShowComposer::class);
//       View::composer('home', TagComposer::class);
//     View::composer('dish.favorites', FavoritesDishesComposer::class);
    }
}
