<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AnalyticsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/videos/search', [VideoController::class, 'search']);
    Route::get('/videos/{id}', [VideoController::class, 'show']);
    Route::get('/videos/{id}/recommendations', [VideoController::class, 'recommendations']);

    Route::any('/videos/{id}/messages', [ChatController::class, 'store']);
    Route::delete('/videos/{videoId}/messages/{messageId}', [ChatController::class, 'destroy'])->middleware('role:admin,mod,{video.user_id}_mod');

    Route::post('/videos/{id}/follow', [UserController::class, 'follow']);
    Route::post('/videos/{id}/like', [UserController::class, 'like']);

    Route::post('/analytics', [AnalyticsController::class, 'store']);

    Route::get('/users/{id}/profile', [UserController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
});

