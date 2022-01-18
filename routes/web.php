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


Auth::routes();
// ホームページ表示ルーティング
Route::get('/home', 'Main\HomeController@index')->name('home');

// プラン投稿関連ルーティング
Route::group(['prefix' => 'post'], function(){
  Route::get('/', 'Main\PostController@index')->middleware('auth');
  Route::get('/planedit/{plan_id}', 'Main\PostController@planEditIndex')->middleware('auth');
  Route::get('/spotedit/{spot_id}', 'Main\PostController@spotEditIndex')->middleware('auth');
  Route::post('/create', 'Main\PostController@create')->middleware('auth');
  Route::post('/delete/{plan_id}', 'Main\PostController@delete')->middleware('auth');
  Route::post('/planedit/{plan_id}', 'Main\PostController@planUpdate')->middleware('auth');
  Route::post('/spotedit/{spot_id}', 'Main\PostController@spotUpdate')->middleware('auth');
});

// 投稿プラン閲覧関連ルーティング
Route::group(['prefix' => 'index'], function(){
  Route::get('/favspot', 'Main\PlanpageController@registarFavSpot')->middleware('auth');
  Route::get('/unfavspot', 'Main\PlanpageController@deleteFavSpot')->middleware('auth');
  Route::get('/favplan', 'Main\PlanpageController@registarFavPlan')->middleware('auth');
  Route::get('/unfavplan', 'Main\PlanpageController@deleteFavPlan')->middleware('auth');
  Route::get('/{plan_id}', 'Main\PlanpageController@index');
  Route::get('/spot/{spot_id}', 'Main\PlanpageController@indexSpot');
});

// マイページ関連ルーティング
Route::group(['prefix' => 'mypage'], function(){
  Route::get('/{user_id}', 'Main\MypageController@index');
  Route::get('myplan/nextplan', 'Main\MypageController@index_nextMyPlan');
  Route::get('favplan/nextplan', 'Main\MypageController@index_nextFavPlan');
  Route::get('favspot/nextspot', 'Main\MypageController@index_nextFavSpot');
});

// 検索ページ関連ルーティング
Route::group(['prefix' => 'search'], function(){
  Route::get('/', 'Main\SearchController@index');
  Route::post('/', 'Main\SearchController@homeSearch');
  Route::get('/nextplan', 'Main\SearchController@planSearch');
  Route::get('/nextspot', 'Main\SearchController@spotSearch');
  Route::get('/nextuser', 'Main\SearchController@userSearch');
});

// コメント関連ルーティング
Route::group(['prefix' => 'comment'], function(){
  Route::get('/create', 'Main\CommentController@index')->middleware('auth');
  Route::post('/create', 'Main\CommentController@create')->middleware('auth');
  Route::get('/show', 'Main\CommentController@show');
  Route::get('/delete', 'Main\CommentController@delete');
});

// プロフィール関連ルーティング
Route::group(['prefix' => 'profile'], function(){
  Route::get('/edit', 'Main\ProfileController@index')->middleware('auth');
  Route::post('/update', 'Main\ProfileController@update')->middleware('auth');
});

//ユーザーページ関連ルーティング
Route::group(['prefix' => 'users'], function(){
  Route::get('/index', 'Main\UsersViewController@index')->middleware('auth');
  Route::get('/follow/{user_id}', 'Main\UsersViewController@follow')->middleware('auth');
  Route::get('/unfollow/{user_id}', 'Main\UsersViewController@unfollow')->middleware('auth');
  Route::get('/all/nextuser', 'Main\UsersViewController@index_nextAllUser')->middleware('auth');
  Route::get('/favorite/nextuser', 'Main\UsersViewController@index_nextFavoriteUser')->middleware('auth');
  Route::get('/follower/nextuser', 'Main\UsersViewController@index_nextFollwerUser')->middleware('auth');
});
