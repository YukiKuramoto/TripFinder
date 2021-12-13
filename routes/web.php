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
  Route::get('/planedit/{plan_id}', 'PostController@edit')->middleware('auth');
  Route::get('/spotedit/{spot_id}', 'PostController@spotedit')->middleware('auth');
  Route::post('/create', 'PostController@create')->middleware('auth');
  Route::post('/delete/{plan_id}', 'PostController@delete')->middleware('auth');
  Route::post('/planedit/{plan_id}', 'PostController@planUpdate')->middleware('auth');
  Route::post('/spotedit/{spot_id}', 'PostController@spotUpdate')->middleware('auth');
});

Route::group(['prefix' => 'index'], function(){
  Route::get('/favspot', 'PlanpageController@registarFavSpot')->middleware('auth');
  Route::get('/unfavspot', 'PlanpageController@deleteFavSpot')->middleware('auth');
  Route::get('/favplan', 'PlanpageController@registarFavPlan')->middleware('auth');
  Route::get('/unfavplan', 'PlanpageController@deleteFavPlan')->middleware('auth');
  Route::get('/{plan_id}', 'PlanpageController@index');
  Route::get('/spot/{spot_id}', 'PlanpageController@indexSpot');
});

Route::group(['prefix' => 'mypage'], function(){
  Route::get('/{user_id}', 'MypageController@index');
  Route::post('/nextplan', 'MypageController@index_nextplan');
  Route::post('/nextspot', 'MypageController@index_nextspot');
});

Route::group(['prefix' => 'search'], function(){
  Route::get('/', 'SearchController@index');
  Route::post('/areasearch', 'SearchController@homeSearch');
  Route::post('/nextplan', 'SearchController@planSearch');
  Route::post('/nextspot', 'SearchController@spotSearch');
  Route::post('/nextuser', 'SearchController@userSearch');
});

Route::group(['prefix' => 'comment'], function(){
  Route::get('/create', 'CommentController@index')->middleware('auth');
  Route::post('/create', 'CommentController@create')->middleware('auth');
  Route::get('/show', 'CommentController@show');
  Route::get('/delete', 'CommentController@delete');
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

Route::post('/register', 'Auth\RegisterController@register')->name('register');
