<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;

class RecipeController extends Controller
{
    public function index (): View
    {

    }

    public function create (): View
    {
        return view('recipes.create');
    }

}
