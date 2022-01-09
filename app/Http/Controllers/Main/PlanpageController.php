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
    public function index($plan_id)
    {
      $plan = Plan::find($plan_id);
      $current_user_id = Auth::id() != [] ? Auth::id() : 'undefined_user';
      $current_user_id = Auth::id();
      $current_user = User::find($current_user_id);
      $plan->tags;
      $spot_comment = [];
      $dayInfo = [];
      $count = 0;

      // プランお気に入り状況取得
      $fav_plan = FavPlan::where('user_id', $current_user_id)->where('plan_id', $plan_id)->get();
      if(count($fav_plan) == 0){
        $plan->fav_status = false;
      }else{
        $plan->fav_status = true;
      };

      foreach($plan->spots as $index => $spot){

        $comment = [];
        // スポット詳細情報取得
        $spot->spot_count = $index;
        $spot->tags;
        $spot->images;
        $spot->favs;
        $spot->user;
        $spot->links;
        foreach($spot->comments as $item){
          $user = User::find($item->user_id);
          $item->user_name = $user->name;
          $item->user_image = $user->image_path;
        }

        // スポットお気に入り状況取得
        $fav_spot = FavSpot::where('user_id', $current_user_id)->where('spot_id', $spot->id)->get();
        if(count($fav_spot) == 0){
          $spot->fav_status = false;
        }else{
          $spot->fav_status = true;
        };

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


    public function indexSpot($spot_id)
    {
      $spot = Spot::find($spot_id);
      $plan = $spot->plans;
      $spot->user;
      $spot->images;
      $spot->tags;
      $spot->links;
      $spot['spot_count'] = 0;


      foreach($spot->comments as $item){
        $user = User::find($item->user_id);
        $item->user_name = $user->name;
        $item->user_image = $user->image_path;
      }

      $login_uid = Auth::id() != [] ? Auth::id() : 'undefined_user';
      if($login_uid != 'undefined_user'){
        // $current_user = User::find($current_user_id);
        // スポットお気に入り状況取得
        $fav_spot = FavSpot::where('user_id', $login_uid)->where('spot_id', $spot->id)->get();
        if(count($fav_spot) == 0){
          $spot->fav_status = false;
        }else{
          $spot->fav_status = true;
        };
      }

      return view('planpage.spot', ['spot' => $spot, 'plan' => $plan, 'login_uid' => $login_uid]);
    }


    public function registarFavPlan(Request $request)
    {
      $current_user_id = Auth::id();
      $fav_plan = new FavPlan;
      $fav_plan->user_id = $current_user_id;
      $fav_plan->plan_id = $request->planId;
      $fav_plan->save();

      return redirect()->action('Main\PlanpageController@index', ['plan_id' => $request->planId]);
    }


    public function deleteFavPlan(Request $request)
    {
      $current_user_id = Auth::id();
      FavPlan::where('user_id', $current_user_id)->where('plan_id', $request->planId)->delete();

      return redirect()->action('Main\PlanpageController@index', ['plan_id' => $request->planId]);
    }


    public function registarFavSpot(Request $request)
    {
      // dd($request);
      $current_user_id = Auth::id();
      $fav_spot = new FavSpot;
      $fav_spot->user_id = $current_user_id;
      $fav_spot->spot_id = $request->spotId;
      $fav_spot->save();

      return redirect()->action('Main\PlanpageController@index', ['plan_id' => $request->planId]);
    }


    public function deleteFavSpot(Request $request)
    {
      $current_user_id = Auth::id();
      FavSpot::where('user_id', $current_user_id)->where('spot_id', $request->spotId)->delete();

      return redirect()->action('Main\PlanpageController@index', ['plan_id' => $request->planId]);
    }
}
