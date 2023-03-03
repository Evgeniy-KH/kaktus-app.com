<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Http\Requests\UnlikeRequest;
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
}
