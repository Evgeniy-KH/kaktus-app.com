<?php

namespace App\View\Composer;

use App\Models\Dish;
use App\Models\FavoriteDish;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DishCreateComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('user', auth()->user());
    }
}
