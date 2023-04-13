<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MarkController;
use App\Http\Controllers\API\ModelController;
use App\Http\Controllers\API\CarController;
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

Route::post('sign-in', [AuthController::class, 'signIn']);

Route::middleware(['auth:sanctum'])->group(static function () {
    Route::post('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware('admin_middleware')->group(function () {
        Route::resource('mark', MarkController::class);
        Route::resource('model', ModelController::class);
        Route::resource('car', CarController::class);
        Route::post('update-car-status/{id}', [CarController::class, 'updateStatus']);
    });
});
