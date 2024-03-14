<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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


Route::controller(AuthController::class)->prefix('auth')->group(function () : void {
    Route::post('/signup', 'signUp');
    Route::post('/signin', 'signIn');
});


Route::middleware('auth:sanctum')->group(function () : void {
    Route::controller(AuthController::class)->prefix('auth')->group(function () : void {
        Route::get('/user', 'index');
        Route::put('/user', 'update');
        Route::post('/signout', 'signOut');
    });
});
