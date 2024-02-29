<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('user-management/users')->group(function () {
    Route::post('sign-up', [UserController::class, 'signUp']);
    Route::post('sign-in', [UserController::class, 'signIn']);
});

Route::middleware('auth')->prefix('/food-order')->group(function(){
    Route::get("foods", [FoodController::class, 'index']);
    Route::put("foods/{foodId}/favorites", [FoodController::class, 'toggleFavorite']);
    Route::post("cart", [FoodController::class, 'addCart']);
    Route::delete("cart/{foodId}", [FoodController::class, 'removeCart']);
});
