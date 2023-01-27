<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PersonalController extends Controller
{
    public function edit (int $id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);

        return response()->json($user);

    }

    public function updatePassword (int $id,UpdatePasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = User::find($id);
        $oldPassword = $user->password;


       if(Hash::check($data['old_password'], $user->password)) {
           $user->password = Hash::make($data['password']);
           $returnData = $user;
           $code = 200;
       } else {
           $returnData = array(
               'status' => 'error',
               'message' => 'Your old password is incorrect'
           );
           $code = 422;
       }

        return response()->json($returnData, $code);
    }

}
