<?php
declare(strict_types=1);

use App\Http\Controllers\SocialController;
use App\Http\Controllers\User\Dish\DishController;
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
Route::get('/catalog/dish/getTags', [App\Http\Controllers\HomeController::class, 'getTags']);
Route::get('/catalog/dish/{dish}', [App\Http\Controllers\HomeController::class, 'show'])->name('home.show');
Route::get('/catalog/dish/show/{dish}', [App\Http\Controllers\HomeController::class, 'showDish']);


Route::get('auth/facebook', [SocialController::class, 'facebookRedirect']);
Route::get('auth/facebook/callback', [SocialController::class, 'loginWithFacebook']);

Route::group(['namespace'=>'App\Http\Controllers\User', 'prefix' => 'user','middleware' => ['auth']], function () {
    Route::view('{user}', 'user.edit');
    Route::post('{user}/', [UserController::class, 'updateUser']);
    Route::post('image/{user}/', [UserController::class, 'updateImage']);

    Route::group(['prefix' => 'dish'], function () {
        Route::get('/', [DishController::class,'index'])->name('user.dish.index');
        Route::get('/create', [DishController::class,'create'])->name('user.dish.create');
        Route::post('/', [DishController::class,'store'])->name('user.dish.store');
        Route::get('/{dish}/edit', [DishController::class,'editView'])->name('user.dish.edit');
        Route::get('/{dish}/editData', [DishController::class,'editData']);
        Route::patch('/{dish}', [DishController::class,'update'])->name('user.dish.update');
        Route::delete('/{dish}', [DishController::class,'delete'])->name('user.dish.delete');
    });


});

