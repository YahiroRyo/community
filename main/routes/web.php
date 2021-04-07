<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CommunityController;
use App\Http\Controllers\BellController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

Route::get('/api/get/bells',                    [BellController::class, 'getBells']);

Route::post('/api/post/cancel-join-community',      [CommunityController::class, 'cancelJoinCommunity']);
Route::post('/api/post/can-i-join-community',       [CommunityController::class, 'canIJoinCommunity']);
Route::post('/api/post/dont-join-community',        [CommunityController::class, 'dontJoinCommunity']);
Route::post('/api/post/create-community',           [CommunityController::class, 'createCommunity']);
Route::post('/api/post/join-community',             [CommunityController::class, 'joinCommunity']);
Route::get('/api/get/can-join-community',           [CommunityController::class, 'getCanJoinCommunity']);
Route::get('/api/get/communities',                  [CommunityController::class, 'getCommunities']);

Route::post('/api/post/refresh-user-profile-image', [UserController::class, 'refreshUserProfileImage']);
Route::post('/api/post/refresh-user-profile',       [UserController::class, 'refreshUserProfile']);
Route::post('/api/post/register-user',              [UserController::class, 'registerUser']);
Route::get('/api/get/user-profile',                 [UserController::class, 'getUserProfile']);
Route::get('/api/get/my-user-data',                 [UserController::class, 'getMyUserData']);

Route::post('/api/post/create-community-post',      [PostController::class, 'createCommunityPost']);
Route::post('/api/post/create-responce-post',       [PostController::class, 'createResponcePost']);
Route::post('/api/post/create-post',                [PostController::class, 'createPost']);
Route::post('/api/post/delete-post',                [PostController::class, 'deletePost']);
Route::post('/api/post/great-post',                 [PostController::class, 'greatPost']);
Route::get('/api/get/community-posts',              [PostController::class, 'getCommunityPosts']);
Route::get('/api/get/responce-posts',               [PostController::class, 'getResponcePosts']);
Route::get('/api/get/global-posts',                 [PostController::class, 'getGlobalPosts']);
Route::get('/api/get/users-posts',                  [PostController::class, 'getUsersPosts']);

Route::get('/***REMOVED***any?***REMOVED***', function () ***REMOVED***
    return view('index');
***REMOVED***)->where('any', '.+');