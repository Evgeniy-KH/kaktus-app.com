<?php

namespace App\View\Composer;

use App\Models\User;
use Illuminate\View\View;

class TagComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('user', auth()->user());
    }
}
