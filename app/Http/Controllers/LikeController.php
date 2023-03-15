<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Http\Requests\UnlikeRequest;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(LikeRequest $request)
    {
        $request->user()->like($request->likeable());

        return response()->json();
    }

    public function unlike(UnlikeRequest $request)
    {
        $request->user()->unlike($request->likeable());

        return response()->json();
    }

    public function users(Request $request)
    {
        $usersId = $request->usersId;
        $users = User::find($usersId)->take(4);;

        return response()->json($users);
    }

    public function likedDishes()
    {
        $likedDishes = auth()->user()->likes()->where('likeable_type', '=', 'App\\Models\\Dish')->get();
        $likedDishesId = [];

        foreach ($likedDishes as $likedDish) {
            array_push($likedDishesId, $likedDish['likeable_id']);
        }

        $returnData = Dish::with('dishImages', 'tags')->whereIn('id', $likedDishesId)->get();

        return response()->json($returnData);
    }
}
