<?php

use Illuminate\Http\Request;

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
 
Route::get('test', function () {
    return response()->json('Pong');
});
Route::post('/users/signin', [UserController::class, 'userLogin'])->name('api.users.login');
Route::post('/users/signup', [UserController::class, 'createUser'])->name('api.users.register');