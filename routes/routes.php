<?php

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

Route::middleware('auth')->get('/user', function(){
    return response()->json(request()->user);
});
