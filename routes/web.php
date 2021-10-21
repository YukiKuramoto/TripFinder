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

Route::group(['prefix' => 'post'], function(){
  Route::get('/', 'PostController@show')->middleware('auth');
  Route::get('/edit/{plan_id}', 'PostController@edit')->middleware('auth');
  Route::post('/create', 'PostController@create')->middleware('auth');
  Route::post('/delete/{plan_id}', 'PostController@delete')->middleware('auth');
  Route::post('/update/{plan_id}', 'PostController@update')->middleware('auth');
});

Route::group(['prefix' => 'index'], function(){
  Route::get('/favspot', 'PlanpageController@registarFavSpot');
  Route::get('/unfavspot', 'PlanpageController@deleteFavSpot');
  Route::get('/favplan', 'PlanpageController@registarFavPlan');
  Route::get('/unfavplan', 'PlanpageController@deleteFavPlan');
  Route::get('/{plan_id}', 'PlanpageController@index');
  Route::get('/spot/{spot_id}', 'PlanpageController@indexSpot');
});

Route::group(['prefix' => 'mypage'], function(){
  Route::get('/{user_id}', 'MypageController@index')->middleware('auth');
  Route::post('/nextplan', 'MypageController@index_nextplan')->middleware('auth');
  Route::post('/nextspot', 'MypageController@index_nextspot')->middleware('auth');
});

Route::group(['prefix' => 'search'], function(){
  Route::get('/', 'SearchController@index');
  Route::post('/main', 'SearchController@mainSearch');
  Route::post('/nextplan', 'SearchController@index_nextplan');
  Route::post('/nextspot', 'SearchController@index_nextspot');
  Route::post('/nextuser', 'SearchController@index_nextuser');
  Route::post('/plan', 'SearchController@planSearch');
  Route::post('/spot', 'SearchController@spotSearch');
});

Route::group(['prefix' => 'comment'], function(){
  Route::get('/create', 'CommentController@index')->middleware('auth');
  Route::post('/create', 'CommentController@create')->middleware('auth');
});

Route::group(['prefix' => 'profile'], function(){
  Route::get('/edit', 'ProfileController@edit')->middleware('auth');
  Route::post('/update', 'ProfileController@update')->middleware('auth');
});

Route::group(['prefix' => 'users'], function(){
  Route::get('/index', 'UsersViewController@index')->middleware('auth');
  Route::get('/follow/{user_id}', 'UsersViewController@follow')->middleware('auth');
  Route::get('/unfollow/{user_id}', 'UsersViewController@unfollow')->middleware('auth');
  Route::post('/nextuser', 'UsersViewController@index_nextuser')->middleware('auth');
});

Route::get('/show', 'PostController@index');
Route::get('/home', 'Home\HomeController@index')->name('home');
Route::post('/home/logout', 'UserAuthController@logoutuser');
Auth::routes();
