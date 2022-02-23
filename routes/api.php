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

		// Statements
		Route::group(['prefix' => 'statements'], function () {
			Route::get('/', 'Api\StatementController@index')->middleware('permission:statements.index');
			Route::post('/', 'Api\StatementController@store')->middleware('permission:statements.create');
			Route::get('/{statement:id}', 'Api\StatementController@show')->middleware('permission:statements.show');
			Route::put('/{statement:id}', 'Api\StatementController@update')->middleware('permission:statements.edit');
			Route::delete('/{statement:id}', 'Api\StatementController@destroy')->middleware('permission:statements.delete');
			Route::put('/{statement:id}/activate', 'Api\StatementController@activate')->middleware('permission:statements.active');
			Route::put('/{statement:id}/deactivate', 'Api\StatementController@deactivate')->middleware('permission:statements.deactive');

			// Resolutions
			Route::group(['prefix' => '{statement:id}/resolutions'], function () {
				Route::get('/', 'Api\ResolutionController@index')->middleware('permission:statements.show');
				Route::post('/', 'Api\ResolutionController@store')->middleware('permission:resolutions.create');
				Route::get('/{resolution:id}', 'Api\ResolutionController@show')->middleware('permission:resolutions.show');
				Route::put('/{resolution:id}', 'Api\ResolutionController@update')->middleware('permission:resolutions.edit');
				Route::post('/{resolution:id}/files', 'Api\ResolutionController@uploadFile')->middleware('permission:resolutions.edit');
				Route::post('/{resolution:id}/files/{file:id}', 'Api\ResolutionController@destroyFile')->middleware('permission:resolutions.edit');
				Route::delete('/{resolution:id}', 'Api\ResolutionController@destroy')->middleware('permission:resolutions.delete');
			});
		});
	});
});