<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Plan;
use App\Spot;
use App\Tag;
use App\PlanTag;
use App\SpotTag;
use App\User;

class MypageController extends Controller
{
    public function index($user_id)
    {
      $user = User::find($user_id);
      $plans = $this->getPlans($user->plans);
      $plans_fav = $this->getPlans($user->favPlans);
      $spots_fav = $this->getSpots($user->favSpots);

      return view('mypage.index', [
        'user' => $user,
        'plans' => $plans,
        'plans_fav' => $plans_fav,
        'spots_fav' => $spots_fav,
      ]);
    }



    public function index_nextplan(Request $request)
    {
      $user_id = $request->all()['user'];
      $page_index = $request->all()['page'];
      $type = $request->all()['parameter'];
      $user = User::find($user_id);

      switch ($type) {
        case 'myplan':
          $plans = $this->getPlans($user->plans);
          break;

        case 'favplan':
          $plans = $this->getPlans($user->favPlans);
          break;
      }

      return ([
        'user' => $user,
        'plans' => $plans[$page_index - 1],
        'plan_length' => count($plans),
        'plan_current' => $page_index,
      ]);
    }


    public function index_nextspot(Request $request)
    {
      $user_id = $request->all()['user'];
      $page_index = $request->all()['page'];
      $user = User::find($user_id);
      $spots = $this->getSpots($user->favSpots);

      return ([
        'user' => $user,
        'spots' => $spots[$page_index - 1],
        'spot_length' => count($spots),
        'spot_current' => $page_index,
      ]);
    }


    public function getPlans($plans_all)
    {
      $item_amount = 3;
      $plans = [];
      $plans[0] = [];
      $i = 0;

      foreach ($plans_all as $index => $plan) {
        $count = $index + 1;
        $plan->spots;
        $plan->favs;
        $plan->tags;
        foreach ($plan->spots as $spot) {
          $spot->images;
        }
        array_push($plans[$i], $plan);

        // 最終インデックスまで到達したら配列の要素を増やさないようBreak
        if($index + 1 == count($plans_all)){
          break;
        }
        // 3個区切りでプランを表示
        if($count % $item_amount == 0){
          $i++;
          $plans[$i] = [];
        }
      }

      return $plans;
    }


    public function getSpots($spots_all)
    {
      $item_amount = 4;   //ページネーション時、1ページに表示させたいアイテム数
      $spots = [];
      $spots[0] = [];
      $i = 0;

      foreach ($spots_all as $index => $spot) {
        $count = $index + 1;
        $spot->images;
        $spot->favs;
        $spot->user;

        array_push($spots[$i], $spot);

        if($index + 1 == count($spots_all)){
          break;
        }

        // ４個区切りでスポットを表示
        if($count % $item_amount == 0){
          $i++;
          $spots[$i] = [];
        }
      }

      return $spots;
    }



    public function showpost ($user_id, $plan_id)
    {
      dd('user_id' . $user_id . ', plan_id' . $plan_id);
    }
}
