<?php
declare(strict_types=1);

use App\Http\Controllers\Dish\DishController;
use App\Http\Controllers\Dish\FavoriteDishController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\Tag\TagController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::view('/home', 'home');
Route::get('/catalog', [DishController::class, 'index'])->name('home.catalog');
Route::get('/catalog/dish/getTags', [TagController::class, 'index']);
Route::view('/catalog/dish/{id}', 'dish.show');
Route::get('/catalog/dish/show/{id}', [DishController::class, 'show']);
Route::get('auth/facebook', [SocialController::class, 'facebookRedirect']);
Route::get('auth/facebook/callback', [SocialController::class, 'loginWithFacebook']);

Route::group(['namespace' => 'App\Http\Controllers\User', 'prefix' => 'user', 'middleware' => ['auth']], function () {
    Route::get('/dishes', [UserController::class, 'usersDishes']);
    Route::view('/favorites_dishes', 'dish.favorites');
    Route::view('/liked_dishes', 'dish.my_liked');
    Route::view('/my_dishes', 'dish.my_dishes');
    Route::view('{id}', 'user.edit');
    Route::post('{id}', [UserController::class, 'update']);
    Route::get('/{userId}/favorite/dishes', [FavoriteDishController::class, 'show']);
    Route::get('/{userId}/favorite/dishes/list', [FavoriteDishController::class, 'index']);

    Route::group(['prefix' => 'dish'], function () {
        Route::view('/create', 'dish.create');
        Route::post('/like', [LikeController::class, 'store']);
        Route::get('/users', [UserController::class, 'show']);
        Route::get('/liked/{id}', [LikeController::class, 'show']);
        Route::delete('/unlike/{userId}/{dishId}', [LikeController::class, 'delete'])->middleware('dish.exist');
        Route::post('/favorite', [FavoriteDishController::class, 'store']);
        Route::post('/disfavouring/{userId}/{dishId}', [FavoriteDishController::class, 'delete'])->middleware('dish.exist');
        Route::group(['middleware' => ['dish.verified']], function () {
            Route::view('/{id}/edit', 'dish.edit');
            Route::get('/{id}/editData', [DishController::class, 'edit']);
            Route::patch('/{id}', [DishController::class, 'update']);
            Route::delete('/{id}', [DishController::class, 'delete']);
        });
    });

});

