<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

if (App::environment() == 'development') {
    Route::get('migrate',   ['as' => 'migrate', 'uses' => 'AppController@migrate']);
    Route::get('clear-cache',   ['as' => 'clear-cache', 'uses' => 'AppController@clearCache']);
}

Route::get('/', ['as' => 'home', 'uses' => 'Controller@redirect' ]);

Route::get('receive-sms', ['as' => 'receive_sms ', 'uses' => 'Controller@receiveSms ' ]);

Auth::routes();

Route::get('dashboard', ['as' => 'admin.dashboard', 'uses' => 'Admin\DashboardController@index']);
Route::get('select-company/{company_id?}', ['as' => 'admin.select_company', 'uses' => 'Admin\AdminController@changeSelectedCompany']);

//Users
Route::group(['prefix' => 'users'], function () {
    Route::get('', ['as' => 'admin.users.index', 'uses' => 'Admin\Users\UsersController@index']);
    Route::get('create', ['as' => 'admin.users.create', 'uses' => 'Admin\Users\UsersController@create']);
    Route::post('store', ['as' => 'admin.users.store', 'uses' => 'Admin\Users\UsersController@store']);
    Route::get('{id}/edit', ['as' => 'admin.users.edit', 'uses' => 'Admin\Users\UsersController@edit']);
    Route::patch('{id}/update', ['as' => 'admin.users.update', 'uses' => 'Admin\Users\UsersController@update']);
    Route::get('{id}/detail', ['as' => 'admin.users.detail', 'uses' => 'Admin\Users\UsersController@detail']);
    Route::get('{id}/delete', ['as' => 'admin.users.delete', 'uses' => 'Admin\Users\UsersController@delete']);
    Route::get('{user_id}/company/{company_id}', ['as' => 'admin.users.company_state', 'uses' => 'Admin\Users\UsersController@switchCompanyState']);
    Route::post('{company_id}/add_to_company', ['as' => 'admin.users.add_to_company', 'uses' => 'Admin\Users\UsersController@addUserToCompany']);
});

//Companies
Route::group(['prefix' => 'companies'], function () {
    Route::get('', ['as' => 'admin.companies.index', 'uses' => 'Admin\CompaniesController@index']);
    Route::get('create', ['as' => 'admin.companies.create', 'uses' => 'Admin\CompaniesController@create']);
    Route::post('store', ['as' => 'admin.companies.store', 'uses' => 'Admin\CompaniesController@store']);
    Route::get('{id}/edit', ['as' => 'admin.companies.edit', 'uses' => 'Admin\CompaniesController@edit']);
    Route::patch('{id}/update', ['as' => 'admin.companies.update', 'uses' => 'Admin\CompaniesController@update']);
    Route::get('{id}/detail', ['as' => 'admin.companies.detail', 'uses' => 'Admin\CompaniesController@detail']);
    Route::post('{id}/add-cert', ['as' => 'admin.companies.add_cert', 'uses' => 'Admin\CompaniesController@addCert']);
    Route::get('{id}/delete', ['as' => 'admin.companies.delete', 'uses' => 'Admin\CompaniesController@delete']);
    Route::post('{id}/change-logo', ['as' => 'admin.companies.change_logo', 'uses' => 'Admin\CompaniesController@changeLogo']);
    Route::get('{id}/delete-logo', ['as' => 'admin.companies.delete_logo', 'uses' => 'Admin\CompaniesController@deleteLogo']);
    Route::get('{id}/phones', ['as' => 'admin.companies.phones', 'uses' => 'Admin\CompaniesController@phones']);
    Route::post('{id}/phones/store', ['as' => 'admin.companies.phones_store', 'uses' => 'Admin\CompaniesController@phoneStore']);
    Route::get('{id}/phones/delete', ['as' => 'admin.companies.phones_delete', 'uses' => 'Admin\CompaniesController@phoneDelete']);
});

//Sales
Route::group(['prefix' => 'sales'], function () {
    Route::get('', ['as' => 'admin.sales.index', 'uses' => 'Admin\SalesController@index']);
    Route::get('#create-new', ['as' => 'admin.sales.create_new', 'uses' => 'Admin\SalesController@index']);
    Route::post('store', ['as' => 'admin.sales.store', 'uses' => 'Admin\SalesController@store']);
    Route::get('{id}/detail', ['as' => 'admin.sales.detail', 'uses' => 'Admin\SalesController@detail']);
    Route::get('{id}/storno', ['as' => 'admin.sales.storno', 'uses' => 'Admin\SalesController@storno']);
    Route::get('{id}/generate-receipt', ['as' => 'admin.sales.generate_receipt', 'uses' => 'Admin\SalesController@generateReceipt']);
    Route::get('{id}/delete', ['as' => 'admin.sales.delete', 'uses' => 'Admin\SalesController@delete']);
    Route::get('not-sent', ['as' => 'admin.sales.not_sent', 'uses' => 'Admin\SalesController@showNotSent']);
    Route::get('sent-again', ['as' => 'admin.sales.try_sent_again', 'uses' => 'Admin\SalesController@TrySentAgain']);
});

//Notes
Route::group(['prefix' => 'notes'], function () {
    Route::get('', ['as' => 'admin.notes.index', 'uses' => 'Admin\NotesController@index']);
    Route::get('create', ['as' => 'admin.notes.create', 'uses' => 'Admin\NotesController@create']);
    Route::post('store/{sale_id?}', ['as' => 'admin.notes.store', 'uses' => 'Admin\NotesController@store']);
    Route::get('{id}/edit', ['as' => 'admin.notes.edit', 'uses' => 'Admin\NotesController@edit']);
    Route::patch('{id}/update', ['as' => 'admin.notes.update', 'uses' => 'Admin\NotesController@update']);
    Route::get('{id}/delete', ['as' => 'admin.notes.delete', 'uses' => 'Admin\NotesController@delete']);
});

//Roles
Route::group(['prefix' => 'roles'], function () {
    Route::get('', ['as' => 'admin.roles.index', 'uses' => 'Admin\RolesController@index']);
    Route::get('switch-role/{u_c_id}/{role_id}/{type}', ['as' => 'admin.roles.switch-role', 'uses' => 'Admin\RolesController@switchRoleUC']);
});

//Import
Route::group(['prefix' => 'import'], function () {
    Route::get('', ['as' => 'admin.import.index', 'uses' => 'Admin\ImportController@index']);
    Route::get('download', ['as' => 'admin.import.download', 'uses' => 'Admin\ImportController@download']);
    Route::post('submit', ['as' => 'admin.import.submit', 'uses' => 'Admin\ImportController@submit']);
});

//Export
Route::group(['prefix' => 'export'], function () {
    Route::get('', ['as' => 'admin.export.index', 'uses' => 'Admin\ExportController@index']);
    Route::post('submit', ['as' => 'admin.export.submit', 'uses' => 'Admin\ExportController@submit']);
});
