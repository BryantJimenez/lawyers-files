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

	// Companies
	Route::prefix('empresas')->group(function () {
		Route::get('/', 'CompanyController@index')->name('companies.index')->middleware('permission:companies.index');
		Route::get('/registrar', 'CompanyController@create')->name('companies.create')->middleware('permission:companies.create');
		Route::post('/', 'CompanyController@store')->name('companies.store')->middleware('permission:companies.create');
		Route::get('/{company:slug}', 'CompanyController@show')->name('companies.show')->middleware('permission:companies.show');
		Route::get('/{company:slug}/editar', 'CompanyController@edit')->name('companies.edit')->middleware('permission:companies.edit');
		Route::put('/{company:slug}', 'CompanyController@update')->name('companies.update')->middleware('permission:companies.edit');
		Route::delete('/{company:slug}', 'CompanyController@destroy')->name('companies.delete')->middleware('permission:companies.delete');
		Route::put('/{company:slug}/activar', 'CompanyController@activate')->name('companies.activate')->middleware('permission:companies.active');
		Route::put('/{company:slug}/desactivar', 'CompanyController@deactivate')->name('companies.deactivate')->middleware('permission:companies.deactive');
	});

	// Statements
	Route::prefix('casos')->group(function () {
		Route::get('/', 'StatementController@index')->name('statements.index')->middleware('permission:statements.index');
		Route::get('/registrar', 'StatementController@create')->name('statements.create')->middleware('permission:statements.create');
		Route::post('/', 'StatementController@store')->name('statements.store')->middleware('permission:statements.create');
		Route::post('/archivos', 'StatementController@fileStore')->name('statements.store.files')->middleware('permission:statements.create');
		Route::get('/{statement:slug}', 'StatementController@show')->name('statements.show')->middleware('permission:statements.show');
		Route::get('/{statement:slug}/editar', 'StatementController@edit')->name('statements.edit')->middleware('permission:statements.edit');
		Route::put('/{statement:slug}', 'StatementController@update')->name('statements.update')->middleware('permission:statements.edit');
		Route::delete('/{statement:slug}', 'StatementController@destroy')->name('statements.delete')->middleware('permission:statements.delete');
		Route::put('/{statement:slug}/activar', 'StatementController@activate')->name('statements.activate')->middleware('permission:statements.active');
		Route::put('/{statement:slug}/desactivar', 'StatementController@deactivate')->name('statements.deactivate')->middleware('permission:statements.deactive');

		// Resolutions
		Route::prefix('{statement:slug}/resoluciones')->group(function () {
			Route::get('/registrar', 'ResolutionController@create')->name('resolutions.create')->middleware('permission:resolutions.create');
			Route::post('/', 'ResolutionController@store')->name('resolutions.store')->middleware('permission:resolutions.create');
			Route::post('/archivos', 'ResolutionController@fileStore')->name('resolutions.store.files')->middleware('permission:resolutions.create');
			Route::get('/{resolution:slug}', 'ResolutionController@show')->name('resolutions.show')->middleware('permission:resolutions.show');
			Route::get('/{resolution:slug}/archivos/{file:id}/descargar', 'ResolutionController@fileDownload')->name('resolutions.show.files')->middleware('permission:resolutions.show');
			Route::get('/{resolution:slug}/editar', 'ResolutionController@edit')->name('resolutions.edit')->middleware('permission:resolutions.edit');
			Route::put('/{resolution:slug}', 'ResolutionController@update')->name('resolutions.update')->middleware('permission:resolutions.edit');
			Route::post('/{resolution:slug}/archivos/editar', 'ResolutionController@fileEdit')->name('resolutions.edit.files')->middleware('permission:resolutions.edit');
			Route::post('/{resolution:slug}/archivos/eliminar', 'ResolutionController@fileDestroy')->name('resolutions.destroy.files')->middleware('permission:resolutions.edit');
			Route::delete('/{resolution:slug}', 'ResolutionController@destroy')->name('resolutions.delete')->middleware('permission:resolutions.delete');
		});
	});
});