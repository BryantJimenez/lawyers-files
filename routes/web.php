<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/////////////////////////////////////// AUTH ////////////////////////////////////////////////////
Auth::routes(['register' => false]);
Route::get('/usuarios/email', 'AdminController@emailVerifyAdmin');

/////////////////////////////////////////////// WEB ////////////////////////////////////////////////
Route::get('/', 'WebController@index')->name('home');

/////////////////////////////////////// ADMIN ///////////////////////////////////////////////////
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin'], function () {
	// Home
	Route::get('/', 'AdminController@index')->name('admin');

	// Profile
	Route::prefix('perfil')->group(function () {
		Route::get('/', 'AdminController@profile')->name('profile');
		Route::get('/editar', 'AdminController@profileEdit')->name('profile.edit');
		Route::put('/', 'AdminController@profileUpdate')->name('profile.update');
	});

	// Users
	Route::prefix('usuarios')->group(function () {
		Route::get('/', 'UserController@index')->name('users.index')->middleware('permission:users.index');
		Route::get('/registrar', 'UserController@create')->name('users.create')->middleware('permission:users.create');
		Route::post('/', 'UserController@store')->name('users.store')->middleware('permission:users.create');
		Route::get('/{user:slug}', 'UserController@show')->name('users.show')->middleware('permission:users.show');
		Route::get('/{user:slug}/editar', 'UserController@edit')->name('users.edit')->middleware('permission:users.edit');
		Route::put('/{user:slug}', 'UserController@update')->name('users.update')->middleware('permission:users.edit');
		Route::delete('/{user:slug}', 'UserController@destroy')->name('users.delete')->middleware('permission:users.delete');
		Route::put('/{user:slug}/activar', 'UserController@activate')->name('users.activate')->middleware('permission:users.active');
		Route::put('/{user:slug}/desactivar', 'UserController@deactivate')->name('users.deactivate')->middleware('permission:users.deactive');
	});
});