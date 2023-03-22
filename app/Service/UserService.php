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
    public final function update(object $userDto, int $id): array
    {
        $user = $this->getUser($id);
        $return = ['success' => true];

        if ($userDto->getName() !== null || $userDto->getBirthday() !== null) {
//          $birthday = Carbon::createFromFormat('m/d/Y', $data['birthday'])->format('Y-m-d');
            $user->update([
                'name' => $userDto->getName(),
                'birthday' => $userDto->getBirthday()
            ]);
        }

        if ($userDto->getCurrentPassword() !== null && $userDto->getPassword() !== null) {

            if (Hash::check($userDto->getCurrentPassword(), $user->password)) {
                $user->update([
                    'password' => Hash::make($userDto->getPassword())
                ]);
            } else {
                $return = array(
                    'success' => false,
                    'code' => '406', ///Unprocessable Content (WebDAV) or 406 Not Acceptable
                    'message' => 'Your current password is incorrect'
                );
            }
        }

        if ($userDto->getAvatarPath() !== null) {
            if ($user->avatar_path) {
                Storage::delete('public/images/' . $user->avatar_path);
            }

            $file = $userDto->getAvatarPath();
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            $user->update(["avatar_path" => $fileName]);
        }

        return $return;
    }

    public function favoriteDishes(array $favoriteDishesArray): array
    {
        $favoriteDishesId = [];

        foreach ($favoriteDishesArray as $favoriteDish) {
            array_push($favoriteDishesId, $favoriteDish['dish_id']);
        }

        $return['data'] = Dish::with('dishImages', 'tags')->whereIn('id', $favoriteDishesId)->paginate(8);
        $return['success'] = true;


        if ($return['data']->isEmpty()) {
            unset($return['data']);
            $return['success'] = false;
            $return['message'] = 'Your favorites list are empty';
            $return['code'] = 422;
        }

        return $return;
    }

    public final function getUser(int $id): User
    {
        return User::find(id: $id);
    }
}
