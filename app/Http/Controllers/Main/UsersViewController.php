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
      $current_user = Auth::user();
      $current_uid = $current_user->id;

      // 全ユーザ取得
      $users = User::all();
      $users = $this->getFollowInfo($users, $current_uid);
      $users = $this->RemakeArray($users, self::userViewNum);

      // Favoriteユーザー取得
      $favorite_users = $current_user->follows;
      $favorite_users = $this->getFollowInfo($favorite_users, $current_uid);
      $favorite_users = $this->RemakeArray($favorite_users, self::userViewNum);

      //Followerユーザー取得
      $follower_users = $current_user->followers;
      $follower_users = $this->getFollowInfo($follower_users, $current_uid);
      $follower_users = $this->RemakeArray($follower_users,self::userViewNum);

      return view('users.index', [
        'users' => $users,
        'login_uid' => $current_uid,
        'favorite_users' => $favorite_users,
        'follower_users' => $follower_users
      ]);
    }


    /**
    * ユーザーフォロー実行function
    *
    * @param string $target_user_id                        対象ユーザーID
    * @return Illuminate\Http\RedirectResponse　　　　      ユーザー一覧画面ビュー
    */
    public function follow($target_uid)
    {
      $current_uid = Auth::id();
      $follow = new Follow;

      // フォローされたユーザーIDを追加
      $follow->followed_user_id = $target_uid;
      // フォローしたユーザーIDを追加
      $follow->follower_user_id = $current_uid;
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
    public function unfollow($target_uid)
    {
      $current_uid = Auth::id();

      // フォロー中間テーブルから削除
      Follow::where('followed_user_id', $target_uid)->where('follower_user_id', $current_uid)->delete();
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
      $current_user = Auth::user();
      $current_uid = $current_user->id;
      $request_form = json_decode($request->all()['data'],true);
      $next_index = $request_form['next_index'];

      //　全ユーザー取得
      $users = User::all();
      // フォロー状況取得
      $users = $this->getFollowInfo($users, $current_uid);
      // ページネーション用の形式に変換
      $users = $this->RemakeArray($users, self::userViewNum);

      return ([
        'response' => $users[$next_index],
        'total_page' => count($users),
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
      $current_user = Auth::user();
      $current_uid = $current_user->id;
      $request_form = json_decode($request->all()['data'],true);
      $next_index = $request_form['next_index'];

      // フォローしているユーザー取得
      $users = $current_user->follows;
      // フォロー状況取得
      $users = $this->getFollowInfo($users, $current_uid);
      // ページネーション用の形式に変換
      $users = $this->RemakeArray($users, self::userViewNum);

      return ([
        'response' => $users[$next_index],
        'total_page' => count($users),
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
      $current_user = Auth::user();
      $current_uid = $current_user->id;
      $request_form = json_decode($request->all()['data'],true);
      $next_index = $request_form['next_index'];

      // // フォロワー情報取得
      $users = $current_user->followers;
      // フォロー状況取得
      $users = $this->getFollowInfo($users, $current_uid);
      // ページネーション用の形式に変換
      $users = $this->RemakeArray($users, self::userViewNum);

      return ([
        'response' => $users[$next_index],
        'total_page' => count($users),
      ]);
    }

}
