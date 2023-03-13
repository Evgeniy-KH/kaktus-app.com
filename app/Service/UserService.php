<?php

namespace App\Service;

use App\Models\Dish;
use App\Models\DishImage;
use App\Models\FavoriteDish;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Exception;
use http\Encoding\Stream\Inflate;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Collection;

class UserService
{
    public function update(array $data, User $user):string
    {
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
        dd($returnData);

        return $returnData;
    }

    public function favoriteDishes(array $favoriteDishes):array
    {
        $favoriteDishesId = [];
        $returnData = [];

        foreach ($favoriteDishes as $favoriteDish) {
            array_push($favoriteDishesId, $favoriteDish['dish_id']);
        }

        $returnData['data'] = Dish::with('dishImages', 'tags')->whereIn('id', $favoriteDishesId)->paginate(8);
        $returnData['status'] = 200;


        if (!$returnData['data']) {
            $returnData['data'] = array(
                'status' => 'error',
                'message' => 'Your your filter doesn\'t\ match any dishes'
            );
            $returnData['status'] = 422;
        }

        return $returnData;
    }
}
