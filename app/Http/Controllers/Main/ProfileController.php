<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use Storage;

class ProfileController extends Controller
{

  /*
  |--------------------------------------------------------------------------
  | Profile Controller
  |--------------------------------------------------------------------------
  |
  | プロフィール情報編集用コントローラー
  | index  : プロフィール変数用ページ表示
  | update : プロフィール情報更新処理
  |
  */

  /**
  * プロフィール編集ページ表示用function
  *
  * @return Illuminate\Contracts\Support\Renderable      プロフィール編集画面用ビュー
  */
    public function index()
    {
      $user_id = Auth::id();
      $user = User::find($user_id);

      return view('profile.index', ['user' => $user]);
    }


    /**
    * プロフィール情報編集用function
    *
    * @param Illuminate\Http\Request $request              Httpリクエスト
    * @return Illuminate\Http\RedirectResponse　　　　      myページ画面ビュー
    */
    public function update(Request $request)
    {
      $user_request = $request->all();
      $current_uid = Auth::id();
      $user = User::find($user_request['user_id']);

      // ユーザー確認
      if($current_uid != $user_request['user_id']){
        return redirect()->action('Main\MypageController@index', ['user_id' => $current_uid]);
      }

      // 画像保存処理
      if (array_key_exists('image_path',$user_request)) {
        $image_path = $user_request['image_path'];
        $path = Storage::disk('s3')->putFile('/', $image_path, 'public');
        $user->image_path = Storage::disk('s3')->url($path);
      }

      // DB保存処理
      $user->name = $user_request['name'];
      $user->comment = $user_request['comment'];
      $user->save();

      // マイページにリダイレクト
      return redirect()->action('Main\MypageController@index', ['user_id' => $user->id]);
    }
}
