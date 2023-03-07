<?php

namespace App\View\Composer;

use App\Models\Tag;
use App\Models\User;
use Illuminate\View\View;

class TagComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('tags', Tag::all());
    }
}
