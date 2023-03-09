<?php

namespace App\Http\Middleware;

use App\Models\Dish;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DishIsValide
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $dishId = $request->route('dishId');
        //Check if dish exist
        $dishExist = Dish::where('id', $dishId)->exists();

        if ($dishExist) {
            //Check if dish belongs to the user
            $dishBelongsToUser = Auth::user()->dishes->contains($dishId);

            if ($dishBelongsToUser) {
                return $next($request);
            } else {
                return response('Unauthorized User', 401);
            }
        } else {
            return response('Invalid dish Id', 400);
        }
    }
}
