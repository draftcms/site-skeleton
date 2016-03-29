<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => 'web'], function () {
    
    Route::auth();

    // social media connect/disconnect routes
	Route::get('/connect/{diver}/callback', 'ProfileController@addProviderCallback');
	Route::get('/connect/{driver}', 'ProfileController@addProvider');
    Route::get('/disconnect/{driver}', 'ProfileController@disconnectProvider');


    // profile routes
    Route::get('/profile', 'ProfileController@form');
	Route::get('/profile/update', 'ProfileController@update');
    Route::resource('/avatar', 'AvatarController');
    

    Route::get('/home', 'HomeController@index');
});

Route::get('/contact/', 'ContactFormController@getForm');
Route::post('/contact/submit', 'ContactFormController@postForm');







