<?php

namespace App\View\Composer;

use App\Models\User;
use Illuminate\View\View;

class UserComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('user', auth()->user());
    }
}
