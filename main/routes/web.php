<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::post('/api/post/register-user', [UserController::class, 'registerUser']);
Route::get('/***REMOVED***any?***REMOVED***', function () ***REMOVED***
    return view('index');
***REMOVED***)->where('any', '.+');