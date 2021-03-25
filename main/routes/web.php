<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CommunityController;
use App\Http\Controllers\BellController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::get('/api/get/bells',                    [BellController::class, 'getBells']);

Route::post('/api/post/cancel-join-community',  [CommunityController::class, 'cancelJoinCommunity']);
Route::post('/api/post/can-i-join-community',   [CommunityController::class, 'canIJoinCommunity']);
Route::post('/api/post/dont-join-community',    [CommunityController::class, 'dontJoinCommunity']);
Route::post('/api/post/create-community',       [CommunityController::class, 'createCommunity']);
Route::post('/api/post/join-community',         [CommunityController::class, 'joinCommunity']);
Route::get('/api/get/communities',              [CommunityController::class, 'getCommunities']);

Route::post('/api/post/refresh-user-profile',   [UserController::class, 'refreshUserProfile']);
Route::post('/api/post/register-user',          [UserController::class, 'registerUser']);
Route::get('/api/get/user-profile',             [UserController::class, 'getUserProfile']);
Route::get('/api/get/my-user-data',             [UserController::class, 'getMyUserData']);

Route::post('/api/post/create-post',            [PostController::class, 'createPost']);
Route::post('/api/post/great-post',             [PostController::class, 'greatPost']);
Route::get('/api/get/global-posts',             [PostController::class, 'getGlobalPosts']);

Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');