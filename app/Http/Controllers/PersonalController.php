<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;

class PersonalController extends Controller
{
    public function edit (int $id): void
    {
        $user = User::find($id);
        dd($user);

    }

}
