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
Route::get('/post/edit/{plan_id}', 'PostController@edit');
Route::get('/show', 'PostController@index');
Route::get('/index/favspot', 'PlanpageController@registarFavSpot');
Route::get('/index/unfavspot', 'PlanpageController@deleteFavSpot');
Route::get('/index/favplan', 'PlanpageController@registarFavPlan');
Route::get('/index/unfavplan', 'PlanpageController@deleteFavPlan');
Route::get('/index/{plan_id}', 'PlanpageController@index');
Route::get('/mypage/{user_id}', 'MypageController@index');
Route::get('/search', 'SearchController@index');
Route::post('/search', 'SearchController@search');
Route::post('/post/create', 'PostController@create');
Route::post('/post/delete/{plan_id}', 'PostController@delete');
Route::post('/post/update/{plan_id}', 'PostController@update');
Route::post('/home/signin', 'UserRegisterController@register');
Route::post('/home/login', 'UserAuthController@authenticate');
Route::post('/home/logout', 'UserAuthController@logoutuser');
Route::get('/comment/create', 'CommentController@index');
Route::post('/comment/create', 'CommentController@create');
Route::get('/profile/edit', 'ProfileController@edit');
Route::post('/profile/update', 'ProfileController@update');
Route::get('/users/index', 'UsersViewController@index');
Route::get('/users/follow/{user_id}', 'UsersViewController@follow');
Route::get('/users/unfollow/{user_id}', 'UsersViewController@unfollow');


Auth::routes();
