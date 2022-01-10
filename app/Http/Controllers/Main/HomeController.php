<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Spot;

class HomeController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Home Controller
  |--------------------------------------------------------------------------
  |
  | ホーム画面表示用コントローラー
  | index   : TripFinderホーム画面表示
  |
  */

    // ホーム画面表示プラン・スポット数
    const planViewNum = 3;
    const spotViewNum = 4;


    /**
    * ホーム画面ビュー表示用function
    *
    * @return Illuminate\Contracts\Support\Renderable      ホーム画面用ビュー
    */
    public function index()
    {
      // 新着プラン取得処理
      $newArrivalPlans = Plan::latest()->take(self::planViewNum)->get();
      $this->getPlans($newArrivalPlans);
      $newArrivalPlans = $this->RemakeArray($newArrivalPlans,self::planViewNum);

      // 人気プラン取得処理
      $popularPlans = Plan::withCount('favs')->orderBy('favs_count', 'desc')->take(self::planViewNum)->get();
      $this->getPlans($popularPlans);
      $popularPlans = $this->RemakeArray($popularPlans,self::planViewNum);

      // 人気スポット取得処理
      $popularSpots = Spot::withCount('favs')->orderBy('favs_count', 'desc')->take(self::spotViewNum)->get();
      $this->getSpots($popularSpots);
      $popularSpots = $this->RemakeArray($popularSpots, 4);

      // ホーム画面ビューをリターン
      return view('home.index', [
        "newArrivalPlans" => $newArrivalPlans,
        "popularPlans" => $popularPlans,
        "popularSpots" => $popularSpots,
        "param_newArrival" => "newArrival",
        "param_popular" => "popular",
      ]);
    }

}
