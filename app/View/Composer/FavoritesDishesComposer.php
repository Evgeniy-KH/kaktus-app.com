<?php

namespace App\View\Composer;

use App\Models\FavoriteDish;
use Illuminate\View\View;

class FavoritesDishesComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('favoritesDishes', auth()->user()->favoriteDishes()->get());
    }
}
