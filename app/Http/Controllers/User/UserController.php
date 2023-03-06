<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Filters\DishFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Dish;
use App\Models\FavoriteDish;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function update(int $id, UpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
        $data = $request->validated();

        if (array_key_exists('name', $data) || array_key_exists('birthday', $data)) {
            $name = $data['name'];
//          $birthday = Carbon::createFromFormat('m/d/Y', $data['birthday'])->format('Y-m-d');
            $birthday = $data['birthday'];
            $user->update([
                'name' => $name,
                'birthday' => $birthday
            ]);
            $status = "success";
            $returnData = $status;
        }

        if (array_key_exists('current_password', $data) && array_key_exists('password', $data)) {

            if (Hash::check($data['current_password'], $user->password)) {
                $user->update([
                    'password' => Hash::make($data['password'])
                ]);
                $status = "success";
                $returnData = $status;
            } else {
                $returnData = array(
                    'status' => '422',
                    'message' => 'Your current password is incorrect'
                );

            }
        }

        if (array_key_exists('avatar_path', $data)) {

            if ($user->avatar_path) {
                Storage::delete('public/images/' . $user->avatar_path);
            }

            $file = $request->file('avatar_path');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            $user->update(["avatar_path" => $fileName]);
            $status = "success";
            $returnData = $status;
        }

        return response()->json($returnData);
    }

    public function getFavoriteDishes(): \Illuminate\Http\JsonResponse
    {
        $favoriteDishes = auth()->user()->favoriteDishes()->get();

        return response()->json($favoriteDishes);
    }

    public function favoritesDishes()
    {
        return view('favorites-dishes');
    }

    public function myFavoritesDishes(DishFilter $filters)
    {
        $favoriteDishes = auth()->user()->favoriteDishes()->get();
        $favoriteDishesId = [];

        foreach ($favoriteDishes as $favoriteDish) {
            array_push($favoriteDishesId, $favoriteDish['dish_id']);
        }

        $returnData = Dish::with('dishImages', 'tags')->whereIn('id', $favoriteDishesId)->paginate(8);
        $code = 200;

        if ($returnData->isEmpty()) {
            $returnData = array(
                'status' => 'error',
                'message' => 'Your your filter doesn\'t\ match any dishes'
            );
            $code = 422;
        }

        return response()->json($returnData, $code);
    }

    public function usersDishes()
    {

        $usersDishes = auth()->user()->dishes()->with('dishImages', 'tags', 'likes')->withCount('likes')->paginate(8);
        $returnData = [];
        $returnData = $usersDishes;
       // $returnData['usersDishes'] = $usersDishes;
//
//        $usersDishesId = [];
//
//        foreach ($usersDishes as $usersDishe) {
//            array_push(  $usersDishesId, $usersDishe['id']);
//        }
//
//        $countFavorited = FavoriteDish::whereIn('dish_id', $usersDishesId)
//            ->orderBy('total', 'asc')
//            ->selectRaw('dish_id, count(*) as total')
//            ->groupBy('dish_id')
//            ->pluck('total','dish_id')->all();
//
//        $returnData['countFavorited'] = $countFavorited;


        return response()->json($returnData);

    }
}
