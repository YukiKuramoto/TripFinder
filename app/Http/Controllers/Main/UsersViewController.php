<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use App\Follow;

class UsersViewController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | UsersView Controller
  |--------------------------------------------------------------------------
  |
  | 全ユーザー、フォローユーザー、フォロワーユーザーの表示・登録・解除用コントローラー
  |
  | index                   : ユーザー一覧表示
  | follow                  : ユーザーフォロー登録処理
  | unfollow                : ユーザーフォロー解除処理
  | index_nextAllUser       : ページネーションボタン押下時次のユーザー一覧取得・リターン
  | index_nextFavoriteUser  : ページネーションボタン押下時次のフォローユーザー一覧取得・リターン
  | index_nextFollwerUser   : ページネーションボタン押下時次のフォロワーユーザー一覧取得・リターン
  |
  */

    // ユーザー一覧画面ユーザー表示数
    const userViewNum = 6;


    /**
    * ユーザー一覧用ビュー表示用function
    *
    * @return Illuminate\Contracts\Support\Renderable      ユーザー一覧用ビュー
    */
    public function index()
    {
      // ログインユーザー取得
      $current_user_id = Auth::id();
      $current_user = User::find($current_user_id);
      $login_uid = Auth::id() != [] ? Auth::id() : 'undefined_user';
      $favorite_users = [];
      $follower_users = [];

      // 全ユーザ取得
      $users = User::all();
      $users = $this->getFollowInfo($users, $current_user_id);
      $users_pagenated = $this->RemakeArray($users, self::userViewNum);

      // Favoriteユーザー取得
      foreach($current_user->follows as $favorite_user){
        array_push($favorite_users, User::find($favorite_user->followed_user_id));
      }
      $favorite_users = $this->getFollowInfo($favorite_users, $current_user_id);
      $favorite_users_pagenated = $this->RemakeArray($favorite_users, self::userViewNum);

      //Followerユーザー取得
      foreach($current_user->followers as $follower_user){
        array_push($follower_users, User::find($follower_user->follower_user_id));
      }
      $follower_users = $this->getFollowInfo($follower_users, $current_user_id);
      $follower_users_pagenated = $this->RemakeArray($follower_users,self::userViewNum);

      return view('users.index', [
        'users' => $users_pagenated,
        'login_uid' => $login_uid,
        'favorite_users' => $favorite_users_pagenated,
        'follower_users' => $follower_users_pagenated
      ]);
    }


    /**
    * ユーザーフォロー実行function
    *
    * @param string $target_user_id                        対象ユーザーID
    * @return Illuminate\Http\RedirectResponse　　　　      ユーザー一覧画面ビュー
    */
    public function follow($target_user_id)
    {
      $user_id = Auth::id();
      $follow = new Follow;

      // フォローされたユーザーIDを追加
      $follow->followed_user_id = $target_user_id;
      // フォローしたユーザーIDを追加
      $follow->follower_user_id = $user_id;
      // 中間テーブルに登録
      $follow->save();
      return redirect('/users/index');
    }


    /**
    * ユーザーフォロー解除実行function
    *
    * @param string $target_user_id                        対象ユーザーID
    * @return Illuminate\Http\RedirectResponse　　　　      ユーザー一覧画面ビュー
    */
    public function unfollow($target_user_id)
    {
      $user_id = Auth::id();
      // フォロー中間テーブルから削除
      $follow_info = Follow::where('followed_user_id', $target_user_id)->where('follower_user_id', $user_id)->delete();
      return redirect('/users/index');
    }


    /**
    * 全ユーザー用ページネーションボタン押下時次のユーザー取得・リターン用function
    *
    * @param  Illuminate\Http\Request $request      Httpリクエスト
    * @return array                                 ユーザー情報レスポンス
    */
    public function index_nextAllUser(Request $request)
    {
      // ログインユーザー取得
      $current_user_id = Auth::id();
      $current_user = User::find($current_user_id);

      //　全ユーザー取得
      $target_users = User::all();
      $page_index = $request->all()['page'];

      // フォロー状況取得
      $target_users = $this->getFollowInfo($target_users, $current_user_id);
      // ページネーション用の形式に変換
      $users_pagenated = $this->RemakeArray($target_users, self::userViewNum);

      return ([
        'response' => $users_pagenated[$page_index - 1],
        'response_length' => count($users_pagenated),
        'users_current' => $page_index,
      ]);
    }


    /**
    * フォローユーザー用ページネーションボタン押下時次のユーザー取得・リターン用function
    *
    * @param  Illuminate\Http\Request $request      Httpリクエスト
    * @return array                                 ユーザー情報レスポンス
    */
    public function index_nextFavoriteUser(Request $request)
    {
      // ログインユーザー取得
      $current_user_id = Auth::id();
      $current_user = User::find($current_user_id);
      $page_index = $request->all()['page'];

      // フォローしているユーザー取得
      $target_users = [];
      foreach($current_user->follows as $favorite_user){
        array_push($target_users, User::find($favorite_user->followed_user_id));
      }

      // フォロー状況取得
      $target_users = $this->getFollowInfo($target_users, $current_user_id);
      // ページネーション用の形式に変換
      $users_pagenated = $this->RemakeArray($target_users, self::userViewNum);

      return ([
        'response' => $users_pagenated[$page_index - 1],
        'response_length' => count($users_pagenated),
        'users_current' => $page_index,
      ]);
    }


    /**
    * フォロワーユーザー用ページネーションボタン押下時次のユーザー取得・リターン用function
    *
    * @param  Illuminate\Http\Request $request      Httpリクエスト
    * @return array                                 ユーザー情報レスポンス
    */
    public function index_nextFollwerUser(Request $request)
    {
      // ログインユーザー取得
      $current_user_id = Auth::id();
      $current_user = User::find($current_user_id);
      $page_index = $request->all()['page'];

      // フォロワー情報取得
      $target_users = [];
      foreach($current_user->followers as $follower_user){
        array_push($target_users, User::find($follower_user->follower_user_id));
      }

      // フォロー状況取得
      $target_users = $this->getFollowInfo($target_users, $current_user_id);
      // ページネーション用の形式に変換
      $users_pagenated = $this->RemakeArray($target_users, self::userViewNum);

      return ([
        'response' => $users_pagenated[$page_index - 1],
        'response_length' => count($users_pagenated),
        'users_current' => $page_index,
      ]);
    }

}
