<?php

namespace App\Http\Middleware;

use App\Models\Dish;
use Closure;
use Illuminate\Http\Request;

class DishIsExist
{

    public function handle(Request $request, Closure $next)
    {
        $isDishExists = Dish::find($request->route('dishId'))->exists();

        if (!$isDishExists) {
            return response('The dish with given id doest exist', 403);
        }

        return $next($request);
    }
}
