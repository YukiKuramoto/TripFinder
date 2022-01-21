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
use App\FavPlan;
use App\FavSpot;

class PlanpageController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Comment Controller
  |--------------------------------------------------------------------------
  |
  | 投稿されたスポットに対するユーザーのコメントの表示、登録削除を行うコントローラー
  |
  | index           : プランビューの表示
  | indexSpot       : スポットビューの表示
  | registarFavPlan : プランお気に入り登録
  | registarFavSpot : スポットお気に入り登録
  | deleteFavPlan   : お気に入りプラン解除
  | deleteFavPlan   : お気に入りスポット解除
  |
  */

    // EagerLoading時に参照するリレーション
    const relation_plan = ['favs', 'tags'];
    const relation_spot = ['tags', 'images', 'favs', 'user', 'links', 'comments.user'];

  /**
  * プランビュー表示用function
  *
  * @param string $plan_id              表示対象プランID
  * @return Illuminate\Contracts\Support\Renderable      プラン表示用ビュー
  */
    public function index($plan_id)
    {
      // プラン・ユーザーの特定、変数初期値セット
      $current_user_id = Auth::id();
      $dayInfo = [];
      $count = 0;

      $plan = Plan::with(self::relation_plan)->find($plan_id);
      $spots = $plan->spots()->with(self::relation_spot)->get();

      // Vue.js表示用オブジェクト作成処理
      foreach($spots as $index => $spot){

        // Vue.js表示用に関連情報を取得
        $spot->spot_count = $index;

        // Vue.js表示に使用するプラン・スポット情報を保有するオブジェクト構造体に作り替え
        if($count < $spot->spot_day){
          $count = $spot->spot_day;
          array_push($dayInfo, ['day_count' => $spot->spot_day-1, 'spotInfo' => [$spot]]);
        }else{
          $index = count($dayInfo) - 1;
          array_push($dayInfo[$index]['spotInfo'],$spot);
        }
      }

      return view('planpage.index', [
        'plan' => $plan,
        'spot' => $dayInfo,
        'login_uid' => $current_user_id,
      ]);
    }


    /**
    * スポットビュー表示用function
    *
    * @param string $spot_id              表示対象スポットID
    * @return Illuminate\Contracts\Support\Renderable      プラン表示用ビュー
    */
    public function indexSpot($spot_id)
    {
      // スポット、ログインユーザーの取得
      $spot = Spot::with(self::relation_spot)->find($spot_id);
      $current_user_id = Auth::id();
      $plan = $spot->plans;

      // Vue.js表示用スポット情報の取得
      $spot['spot_count'] = 0;

      return view('planpage.spot', ['spot' => $spot, 'plan' => $plan, 'login_uid' => $current_user_id]);
    }


    /**
    * お気に入りプラン登録処理
    *
    * @param Illuminate\Http\Request                       Httpリクエスト
    * @return Illuminate\Contracts\Support\Renderable      プラン表示用ビュー
    */
    public function registarFavPlan(Request $request)
    {
      // ユーザーID取得・登録用インスタンス作成
      $current_user_id = Auth::id();
      $fav_plan = new FavPlan;

      // お気に入りしたユーザーのIDとお気に入り対象プランIDを取得・保存
      $fav_plan->user_id = $current_user_id;
      $fav_plan->plan_id = $request->planId;
      $fav_plan->save();

      return redirect()->action('Main\PlanpageController@index', ['plan_id' => $request->planId]);
    }


    /**
    * お気に入りスポット登録処理
    *
    * @param Illuminate\Http\Request                       Httpリクエスト
    * @return Illuminate\Contracts\Support\Renderable      プラン表示用ビュー
    */
    public function registarFavSpot(Request $request)
    {
      // ユーザーID取得・登録用インスタンス作成
      $current_user_id = Auth::id();
      $fav_spot = new FavSpot;

      // お気に入りしたユーザーのIDとお気に入り対象スポットIDを取得・保存
      $fav_spot->user_id = $current_user_id;
      $fav_spot->spot_id = $request->spotId;
      $fav_spot->save();

      return redirect()->action('Main\PlanpageController@index', ['plan_id' => $request->planId]);
    }


    /**
    * お気に入りプラン解除処理
    *
    * @param Illuminate\Http\Request                       Httpリクエスト
    * @return Illuminate\Contracts\Support\Renderable      プラン表示用ビュー
    */
    public function deleteFavPlan(Request $request)
    {
      // ログインユーザーID取得
      $current_user_id = Auth::id();
      // お気に入り解除処理
      FavPlan::where('user_id', $current_user_id)->where('plan_id', $request->planId)->delete();

      return redirect()->action('Main\PlanpageController@index', ['plan_id' => $request->planId]);
    }


    /**
    * お気に入りスポット解除処理
    *
    * @param Illuminate\Http\Request                       Httpリクエスト
    * @return Illuminate\Contracts\Support\Renderable      プラン表示用ビュー
    */
    public function deleteFavSpot(Request $request)
    {
      // ログインユーザーID取得
      $current_user_id = Auth::id();
      // お気に入り解除処理
      FavSpot::where('user_id', $current_user_id)->where('spot_id', $request->spotId)->delete();

      return redirect()->action('Main\PlanpageController@index', ['plan_id' => $request->planId]);
    }



}
