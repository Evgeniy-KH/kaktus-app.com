<?php

namespace App\View\Composer;

use App\Models\Dish;
use App\Models\FavoriteDish;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DishEditComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $id = $view->getData()["id"];
        $view->with('id',  $id);
    }
}
