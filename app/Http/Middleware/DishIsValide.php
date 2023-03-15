<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Dish;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//TODO  не верное имя.
class DishIsValide
{

    public function handle(Request $request, Closure $next): RedirectResponse
    {
        $dish = Dish::find($request->route('dishId'));

        if ($dish) {
            //TODo ты уверена что у тебя тут есть авторизация?!?!?!?!

            // Auth::user()->dishes - возвращает все блюда!!!!
            $dishBelongsToUser = Auth::user()->dishes->contains($request->route('dishId'));
            // ->dishes->contains($dishId) из всех блюд ищешь вот это. 
            // Пользователь, отдай мне все свои блюда, я среди них поищу то которое мне нужно!!!!
            // Пользователь, есть ли у тебя такое блюдо?!??! 
            
            if ($dishBelongsToUser) {
                return $next($request);
            } else {
                //TODO прочитать коды ошибок, которые отдаются, стандратный набор!!!! Какой код ошибки за что озхначает. Какой нужно использовать в данной ситуации!!! Даю подсказку 403!!!!!! Но можешь почитать и найти лучше вариант.
                return response('Unauthorized User', 401);
            }
        } else {
            //return response('Invalid dish Id', 400);
        }
    }
}
