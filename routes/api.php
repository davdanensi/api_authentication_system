<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user_registration', 'UserController@userRegistration')->name('user_registration');
Route::post('confirmPin', 'UserController@confirmPin')->name('confirmPin');
Route::post('login', 'UserController@login')->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('update_profile', 'UserController@updateProfile');
    Route::post('admin_sent_invitation', 'InviteController@adminInviteUser')->name('adminsentinvitation');
});
