<?php
declare(strict_types=1);

use App\Http\Controllers\SocialController;
use App\Http\Controllers\User\PersonalController;
use App\Http\Controllers\User\DishController;
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

Route::get('auth/facebook', [SocialController::class, 'facebookRedirect']);
Route::get('auth/facebook/callback', [SocialController::class, 'loginWithFacebook']);

Route::group(['namespace'=>'App\Http\Controllers', 'prefix' => 'personal','middleware' => ['auth']], function () {
    Route::get('{personal}/edit', [PersonalController::class, 'edit']);
});

Route::group(['namespace'=>'App\Http\Controllers', 'prefix' => 'recipes','middleware' => ['auth']], function () {
    Route::get('/', [DishController::class,'index'])->name('admin.recipes.index');
    Route::get('/create', [DishController::class,'create'])->name('admin.recipes.create');
//    Route::recipes('/', 'StoreController')->name('admin.recipes.store');
//    Route::get('/{recipes}', 'ShowController')->name('admin.recipes.show');
//    Route::get('/{recipes}/edit', 'EditController')->name('admin.recipes.edit');
//    Route::patch('/{recipes}', 'UpdateController')->name('admin.recipes.update');
//    Route::delete('/{recipes}', 'DeleteController')->name('admin.recipes.delete');
});

