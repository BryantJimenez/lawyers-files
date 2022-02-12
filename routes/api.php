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

Route::group(['prefix' => 'v1'], function() {
	/////////////////////////////////////// AUTH ////////////////////////////////////////////////////
	Route::group(['prefix' => 'auth'], function() {
		Route::post('/login', 'Api\AuthController@login');
		Route::prefix('password')->group(function () {
			Route::post('/email', 'Api\AuthController@recovery');
			Route::post('/reset', 'Api\AuthController@reset');
		});
		Route::group(['middleware' => 'auth:api'], function() {
			Route::get('/logout', 'Api\AuthController@logout');
		});
	});

	/////////////////////////////////////// ADMIN ////////////////////////////////////////////////////
	Route::group(['middleware' => 'auth:api'], function () {
		// Profile
		Route::group(['prefix' => 'profile'], function () {
			Route::get('/', 'Api\ProfileController@get');
			Route::put('/', 'Api\ProfileController@update');
			Route::prefix('change')->group(function () {
				Route::post('/password', 'Api\ProfileController@changePassword');
				Route::post('/email', 'Api\ProfileController@changeEmail');
			});
		});
	});
});