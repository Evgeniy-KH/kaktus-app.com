<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Controllers\Dish\DishController;
use App\Models\Dish;
use Closure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DishMiddleware
{
    public function handle(Request $request, Closure $next):Response
    {
        $id = $request->route('id');
        $dish = Dish::find($id);

        if ($dish) {
            $dishBelongsToUser = Auth::user()->dishes->find($id);

//

            if ($dishBelongsToUser) {
//                $response = new RedirectResponse('/user/dish/{{$id}}/edit');
//                $response->setParameter('request', $request);

                // return  redirect()->route('user.dish.edit', ['id' => $id]);
                // return new RedirectResponse("/user/dish/{$id}/edit");
                // return $response;

//                return redirect()->action(
//                    [DishController::class, 'edit'], ['id' => $id]
//                );
               return $next($request);
            } else {
                //TODO прочитать коды ошибок, которые отдаются, стандратный набор!!!! Какой код ошибки за что озхначает. Какой нужно использовать в данной ситуации!!! Даю подсказку 403!!!!!! Но можешь почитать и найти лучше вариант.
                return response('You are not allowed', 403);
            }
        } else {
            return response('You are not allowed', 403);
        }
    }
}
