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

//Auth
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signUp');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

//User
Route::group([
    'prefix' => 'user'
], function () {
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('all', 'UserController@users');
        Route::get('{id}', 'UserController@show');
        Route::put('{id}', 'UserController@edit');
        Route::delete('{id}', 'UserController@delete');
    });
});

//Money Moves
Route::group([
    'prefix' => 'move'
], function () {
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::post('', 'MoveController@create');
        Route::get('', 'MoveController@get');
        Route::get('date/{date}', 'MoveController@getByDate');
        Route::get('tag/{tag}', 'MoveController@getByTag');
        Route::get('{id}', 'MoveController@show');
        Route::put('{id}', 'MoveController@edit');
        Route::delete('{id}', 'MoveController@delete');
    });
});

//Money Sales
Route::group([
    'prefix' => 'sale'
], function () {
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::post('', 'SaleController@create');
        Route::get('', 'SaleController@get');
        Route::get('date/{date}', 'SaleController@getByDate');
        Route::get('tag/{tag}', 'SaleController@getByTag');
        Route::get('{id}', 'SaleController@show');
        Route::put('{id}', 'SaleController@edit');
        Route::delete('{id}', 'SaleController@delete');
    });
});
