<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

Route::post('/api/post/register-user', [UserController::class, 'registerUser']);
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');