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

		// Companies
		Route::group(['prefix' => 'companies'], function () {
			Route::get('/', 'Api\CompanyController@index')->middleware('permission:companies.index');
			Route::post('/', 'Api\CompanyController@store')->middleware('permission:companies.create');
			Route::get('/{company:id}', 'Api\CompanyController@show')->middleware('permission:companies.show');
			Route::put('/{company:id}', 'Api\CompanyController@update')->middleware('permission:companies.edit');
			Route::delete('/{company:id}', 'Api\CompanyController@destroy')->middleware('permission:companies.delete');
			Route::put('/{company:id}/activate', 'Api\CompanyController@activate')->middleware('permission:companies.active');
			Route::put('/{company:id}/deactivate', 'Api\CompanyController@deactivate')->middleware('permission:companies.deactive');
		});
	});
});