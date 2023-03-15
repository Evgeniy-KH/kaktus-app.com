<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dish;

use App\Http\Controllers\Controller;
use App\Service\DishService;

class BaseController extends Controller
{
    public function __construct(public DishService $service)
    {
    }
}
