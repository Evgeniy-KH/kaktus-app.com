<?php

namespace App\Providers;

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
        View::composer('layouts.login', UserComposer::class);
    }
}
