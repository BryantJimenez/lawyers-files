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
Route::get('/', function() {
	return redirect()->route('admin');
});

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

	// Customers
	Route::prefix('clientes')->group(function () {
		Route::get('/', 'CustomerController@index')->name('customers.index')->middleware('permission:customers.index');
		Route::get('/registrar', 'CustomerController@create')->name('customers.create')->middleware('permission:customers.create');
		Route::post('/', 'CustomerController@store')->name('customers.store')->middleware('permission:customers.create');
		Route::get('/{customer:slug}', 'CustomerController@show')->name('customers.show')->middleware('permission:customers.show');
		Route::get('/{customer:slug}/editar', 'CustomerController@edit')->name('customers.edit')->middleware('permission:customers.edit');
		Route::put('/{customer:slug}', 'CustomerController@update')->name('customers.update')->middleware('permission:customers.edit');
		Route::delete('/{customer:slug}', 'CustomerController@destroy')->name('customers.delete')->middleware('permission:customers.delete');
		Route::put('/{customer:slug}/activar', 'CustomerController@activate')->name('customers.activate')->middleware('permission:customers.active');
		Route::put('/{customer:slug}/desactivar', 'CustomerController@deactivate')->name('customers.deactivate')->middleware('permission:customers.deactive');
	});
});