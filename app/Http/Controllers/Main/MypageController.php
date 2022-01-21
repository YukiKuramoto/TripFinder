<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Spot;
use App\Tag;
use App\PlanTag;
use App\SpotTag;
use App\User;

class MypageController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Mypage Controller
  |--------------------------------------------------------------------------
  |
  | マイページ表示制御コントローラー
  | index           : マイページ遷移時マイページ用ビューを表示
  | index_nextplan  : プラン用ページネーションボタン押下時に次の表示用プランを取得・リターン
  | index_nextspot  : スポット用ページネーションボタン押下時に次の表示用スポットを取得・リターン
  |
  */

    // ホーム画面表示プラン・スポット数
    const planViewNum = 3;
    const spotViewNum = 4;
    // EagerLoading時に参照するリレーション
    const relation_plan = ['spots', 'user', 'tags', 'favs', 'images'];
    const relation_spot = ['user', 'tags', 'favs', 'images'];


    /**
    * 対象ユーザーのマイページ画面ビュー表示用function
    *
    * @param  string $user_id                              ユーザーID
    * @return Illuminate\Contracts\Support\Renderable      ホーム画面用ビュー
    */
    public function index($user_id)
    {
      $user = User::find($user_id);
      $login_uid = Auth::id() != [] ? Auth::id() : 'undefined_user';

      //トップページ用プラン情報取得
      $plans = $user->plans()->with(self::relation_plan)->get();
      $plans_fav = $user->favPlans()->with(self::relation_plan)->get();
      $spots_fav = $user->favSpots()->with(self::relation_spot)->get();

      // ページネーション用に配列編集
      $plans = $this->RemakeArray($plans, self::planViewNum);
      $plans_fav = $this->RemakeArray($plans_fav, self::planViewNum);
      $spots_fav = $this->RemakeArray($spots_fav, self::spotViewNum);

      // マイページ取得＆リターン
      return view('mypage.index', [
        'login_uid' => $login_uid,
        'postuser' => $user,
        'plans' => $plans,
        'plans_fav' => $plans_fav,
        'spots_fav' => $spots_fav,
      ]);
    }


    /**
    * プラン用ページネーションボタン押下時次のプラン取得・リターン用function
    *
    * @param  Illuminate\Http\Request $request      Httpリクエスト
    * @return array                                 ページネート後プラン情報、ユーザー情報、ページネート数
    */
    public function index_nextMyPlan(Request $request)
    {
      $request_form = json_decode($request->all()['data'],true);
      // requestから投稿ユーザーを取得
      $postuser = $request_form['search_key'];
      $nextindex = $request_form['next_index'];
      $user = User::find($postuser['id']);
      $plan_relation = ['spots', 'user', 'tags', 'favs', 'images'];

      // 投稿一覧情報取得し、Vue.js表示用に関連情報を取得
      $plans = $user->plans()->with(self::relation_plan)->get();
      // ページネーション用に配列編集
      $plans = $this->RemakeArray($plans, self::planViewNum);

      // ページネート後プラン情報を含む連想配列リターン
      return ([
        'postuser' => $user,
        'response' => $plans[$nextindex],
        'total_page' => count($plans),
      ]);
    }



    /**
    * プラン用ページネーションボタン押下時次のプラン取得・リターン用function
    *
    * @param  Illuminate\Http\Request $request      Httpリクエスト
    * @return array                                 ページネート後プラン情報、ユーザー情報、ページネート数
    */
    public function index_nextFavPlan(Request $request)
    {
      $request_form = json_decode($request->all()['data'],true);
      // requestから投稿ユーザーを取得
      $postuser = $request_form['search_key'];
      $nextindex = $request_form['next_index'];
      $user = User::find($postuser['id']);

      // お気に入り情報取得し、Vue.js表示用に関連情報を取得
      $plans = $user->favPlans()->with(self::relation_plan)->get();
      // ページネーション用に配列編集
      $plans = $this->RemakeArray($plans, self::planViewNum);

      // ページネート後プラン情報を含む連想配列リターン
      return ([
        'search_key' => $user,
        'response' => $plans[$nextindex],
        'total_page' => count($plans),
      ]);
    }


    /**
    * スポット用ページネーションボタン押下時次のスポット取得・リターン用function
    *
    * @param  Illuminate\Http\Request $request      Httpリクエスト
    * @return array                                 ページネート後プラン情報、ユーザー情報、ページネート数
    */
    public function index_nextFavSpot(Request $request)
    {
      $request_form = json_decode($request->all()['data'],true);
      $postuser = $request_form['search_key'];
      $nextindex = $request_form['next_index'];
      $user = User::find($postuser['id']);

      // お気に入り情報取得し、Vue.js表示用に関連情報を取得
      $spots = $user->favSpots()->with(self::relation_spot)->get();
      // ページネーション用に配列編集
      $spots = $this->RemakeArray($spots, self::spotViewNum);

      // ページネート後スポット情報を含む連想配列リターン
      return ([
        'postuser' => $user,
        'response' => $spots[$nextindex],
        'total_page' => count($spots),
      ]);
    }
}
