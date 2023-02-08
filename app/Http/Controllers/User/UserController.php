<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function updateUser(int $id, UpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
        $data = $request->validated();

        if(array_key_exists('name', $data) ||  array_key_exists('birthday', $data)) {
            $name = $data['name'];
//          $birthday = Carbon::createFromFormat('m/d/Y', $data['birthday'])->format('Y-m-d');
            $birthday = $data['birthday'];
            $user->update([
                'name' => $name,
                'birthday' => $birthday
            ]);
            $code = 200;
            $user = User::find($id);
            $returnData = $user;
        }

        if(array_key_exists('current_password', $data) && array_key_exists('password', $data)) {

            if (Hash::check($data['current_password'], $user->password)) {
                $user->update([
                    'password' => Hash::make($data['password'])
                ]);
                $code = 200;
                $user = User::find($id);
                $returnData = $user;
            } else {
                $code = 422;
                $returnData = array(
                    'status' => 'error',
                    'message' => 'Your old password is incorrect'
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
            $user = User::find($id);
            $returnData = $user;
            $code = 200;
       }

        return response()->json($returnData, $code);
    }

//    public function updatePassword(int $id, UpdateRequest $request): \Illuminate\Http\JsonResponse
//    {
//        $data = $request->validated();
//        $user = User::find($id);
//
//        if (Hash::check($data['current_password'], $user->password)) {
//            $user->update([
//                'password' => Hash::make($data['password'])
//            ]);
//            $returnData = $user;
//            $code = 200;
//        } else {
//            $returnData = array(
//                'status' => 'error',
//                'message' => 'Your old password is incorrect'
//            );
//            $code = 422;
//        }
//
//        return response()->json($returnData, $code);
//    }
//
//    public function updateImage(int $id, UpdatePhotoRequest $request): \Illuminate\Http\JsonResponse
//    {
//
//        $user = User::find($id);
//
//        if ($user->image) {
//            Storage::delete('public/images/' . $user->image);
//        }
//
//        $request->hasFile('image');
//        $file = $request->file('image');
//        $fileName = time() . '.' . $file->getClientOriginalExtension();
//        $file->storeAs('public/images', $fileName);
//        $user->update(['image' => $fileName]);
//
//        $returnData = User::find($id);;
//
//        return response()->json($returnData);
//    }

}
