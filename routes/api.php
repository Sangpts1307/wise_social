<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('testview', [App\Http\Controllers\APIController::class, 'testView']);

Route::post('/login', [App\Http\Controllers\APIController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/list-user', [App\Http\Controllers\APIController::class, 'listUser']);
    Route::post('/user-store', [App\Http\Controllers\APIController::class, 'store']);
    Route::patch('/update-password', [App\Http\Controllers\APIController::class, 'updatePassword']);
    Route::delete('/delete-user/{id}', [App\Http\Controllers\APIController::class, 'deleteUser']);

    Route::get('/my-infor', [App\Http\Controllers\UserController::class, 'show']);
});

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

