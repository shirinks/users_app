<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

Route::middleware('auth:sanctum')->group(function () {
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User management routes
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/user/edit/{user_id}', [UserController::class, 'edit']);
    Route::delete('/user/delete', [UserController::class, 'delete']);
});
Route::get('/users', [UserController::class, 'index']);
Route::post('/login', [UserController::class, 'login']);