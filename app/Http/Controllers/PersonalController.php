<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;

class PersonalController extends Controller
{
    public function edit (int $id): View
    {
        return view('personal.edit', compact('id'));
    }

}
