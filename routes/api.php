<?php

use Illuminate\Http\Request;
use App\Http\Controllers\api\UsersController;
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
Route::post('/users/signin', [UsersController::class, 'userLogin'])->name('api.users.login');
Route::post('/users/signup', [UsersController::class, 'createUser'])->name('api.users.register');
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/users/signout', [UsersController::class, 'userLogout'])->name('api.b2b.users.logout');
});