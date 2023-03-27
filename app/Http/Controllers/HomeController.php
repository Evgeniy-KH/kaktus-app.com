<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\DishCollection;
use App\Http\Resources\DishResource;
use App\Http\Resources\TagResource;
use App\Models\Dish;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
}
