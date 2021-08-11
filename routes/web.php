<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'Home\HomeController@index')->name('home');
Route::get('/post', 'PostController@show');
Route::post('/post/create', 'PostController@create');
Route::post('/home/signin', 'UserRegisterController@register');
Route::post('/home/login', 'UserAuthController@authenticate');
Route::post('/home/logout', 'UserAuthController@logoutuser');



Auth::routes();