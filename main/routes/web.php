<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CommunityController;

Route::post('/api/post/register-user', [UserController::class, 'registerUser']);
Route::post('/api/post/refresh-user-profile', [UserController::class, 'refreshUserProfile']);
Route::get('/api/get/user-profile', [UserController::class, 'getUserProfile']);

Route::post('/api/post/create-community', [CommunityController::class, 'createCommunity']);
Route::get('/api/get/communities', [CommunityController::class, 'getCommunities']);

Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');