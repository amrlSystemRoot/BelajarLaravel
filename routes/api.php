<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SekolahController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(
    function ()
    {
        // Route class sekolah
        Route::get('/sekolah', [SekolahController::class, 'index']);
        Route::post('/sekolah', [SekolahController::class, 'store']);
        Route::put('/sekolah/{id}', [SekolahController::class, 'update']);
        Route::delete('/sekolah/{id}', [SekolahController::class, 'destroy']);

        // update user
        Route::get('/user', [AuthController::class, 'index']);
        Route::put('/user', [AuthController::class, 'update']);

        // logout
        Route::post('/logout', [AuthController::class, 'destroy']);
    }
);