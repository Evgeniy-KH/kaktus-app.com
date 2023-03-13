<?php

namespace App\View\Composer;

use App\Models\Dish;
use App\Models\FavoriteDish;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EditDishComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $dishId = $view->getData()["dishId"];
        $view->with('dishId',  $dishId);
    }
}
