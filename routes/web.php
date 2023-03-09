<?php
declare(strict_types=1);

use App\Http\Controllers\Dish\DishController;
use App\Http\Controllers\Dish\FavoriteDishController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SocialController;
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/catalog', [App\Http\Controllers\HomeController::class, 'catalog'])->name('home.catalog');
Route::get('/catalog/pagination', [App\Http\Controllers\HomeController::class, 'paginationAjax'])->name('home.catalog.pagination');
Route::get('/catalog/dish/getTags', [App\Http\Controllers\HomeController::class, 'tags']);
Route::post('/catalog/filter', [App\Http\Controllers\HomeController::class, 'filter']);
Route::get('/catalog/dish/{dish}', [App\Http\Controllers\HomeController::class, 'show'])->name('home.show');
Route::get('/catalog/dish/show/{dish}', [App\Http\Controllers\HomeController::class, 'showDish']);

Route::get('auth/facebook', [SocialController::class, 'facebookRedirect']);
Route::get('auth/facebook/callback', [SocialController::class, 'loginWithFacebook']);

Route::group(['namespace'=>'App\Http\Controllers\User', 'prefix' => 'user','middleware' => ['auth']], function () {
    Route::get('/dishes', [UserController::class, 'usersDishes']);
    Route::view('/favorites_dishes', 'dish.favorites');
    Route::view('/liked_dishes', 'dish.my_liked');
    Route::view('/my_dishes', 'dish.my_dishes');
    Route::view('{userId}', 'user.edit');
    Route::post('{userId}', [UserController::class, 'update']);
    Route::get('/favorite/dish', [UserController::class, 'getFavoriteDishes']);
    Route::get('/favorite/dishes', [UserController::class, 'myFavoritesDishes']);

    Route::group(['prefix' => 'dish'], function () {
        Route::get('/users', [LikeController::class,'users'])->name('user.dish.users');
        Route::post('/like',  [LikeController::class, 'like'])->name('like');
        Route::get('/liked',  [LikeController::class, 'likedDishes'])->name('liked.dishes');
        Route::delete('/unlike', [LikeController::class, 'unlike'])->name('unlike');
        Route::get('/', [DishController::class,'index'])->name('user.dish.index');
        Route::get('/create', [DishController::class,'create'])->name('user.dish.create');
        Route::post('/store', [DishController::class,'store'])->name('user.dish.store');
        Route::post('/favorite', [FavoriteDishController::class, 'addToFavoriteDish']);
        Route::post('/disfavouring', [FavoriteDishController::class, 'removeFromFavoriteDish']);
        Route::get('/{dishId}/edit', [DishController::class,'editView'])->name('user.dish.edit');
        Route::get('/{dishId}/editData', [DishController::class,'editData']);
        Route::patch('/{dishId}', [DishController::class,'update'])->name('user.dish.update');
        Route::delete('/{dishId}', [DishController::class,'delete'])->name('user.dish.delete');
    });

});

