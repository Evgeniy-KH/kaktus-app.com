<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Controllers\Dish\DishController;
use App\Models\Dish;
use Closure;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DishMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');

//        $isExistsDish = Auth::user()
//        ->whereHas('dishes', function ($query) use ($id) {
//            $query->first($id);
//        })
//        ->doesntExist(); ////Column not found: 1054 Unknown column 'users.id' in 'where clause

        $isExistsDish = Auth::user()
            ->hasDish(id: $id)
            ->doesntExist();

        if ($isExistsDish) {
            return response('You are not allowed', 403);
        }

        return $next($request);
    }
}
