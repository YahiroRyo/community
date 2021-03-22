<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::post('/api/post/register-user', [UserController::class, 'registerUser']);
Route::get('/api/get/user-profile', [UserController::class, 'getUserProfile']);
Route::post('/api/post/refresh-user-profile', [UserController::class, 'refreshUserProfile']);
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');