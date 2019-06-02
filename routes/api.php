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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login', 'Api\PassportController@login');
Route::post('register', 'Api\PassportController@register');

Route::middleware('auth:api')->namespace('Api')->group(function () {
    // 用户
    Route::apiResource('/user', 'UserController');
    //当前用户
    Route::get('/current_user','UserController@getCurrentUserInfo');
});

