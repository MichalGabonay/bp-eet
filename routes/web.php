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

    // Debug bar TODO: pozriet na to
//    Route::get('/_debugbar/assets/stylesheets', ['as' => 'debugbar-css', 'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@css']);
//    Route::get('/_debugbar/assets/javascript', ['as' => 'debugbar-js', 'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@js']);
//    Route::get('/_debugbar/open', ['as' => 'debugbar-open', 'uses' => '\Barryvdh\Debugbar\Controllers\OpenController@handler']);
}


Route::get('/', ['as' => 'home', 'uses' => 'Controller@redirect' ]);


Auth::routes();

Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);
Route::get('dashboard', ['as' => 'admin.dashboard', 'uses' => 'Admin\DashboardController@index']);

Route::group(['prefix' => 'users'], function () {
    Route::get('', ['as' => 'admin.users.index', 'uses' => 'Admin\Users\UsersController@index']);
});

//Route::get('/dashboard', 'Admin\DashboardController@index');
