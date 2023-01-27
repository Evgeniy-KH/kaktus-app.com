<?php

use App\Http\Controllers\Api\PersonalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace'=>'App\Http\Controllers', 'prefix' => 'personal','middleware' => ['auth']], function () {
//    Route::get('/', [PersonalController::class, 'index'])->name('api.personal.index');
//    Route::post('/', [PersonalController::class, 'store'])->name('api.personal.store');
     Route::get('/{personal}/edit', [PersonalController::class, 'edit']);
     Route::patch('password/{personal}/', [PersonalController::class, 'updatePassword']);
//    Route::delete('/{personal}', [PersonalController::class, 'delete'])->name('api.personal.delete');
});
