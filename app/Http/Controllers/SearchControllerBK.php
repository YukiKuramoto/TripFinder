<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Plan;
use App\User;
use App\Spot;
use App\Tag;

class SearchController extends Controller
{
    public function index()
    {
      return view('searchpage.index');
    }


    public function mainSearch(Request $request)
    {
      $search_type = $request->all()['search_type'];
      $search_word = $request->all()['search_word'];
      $old_info = ['type' => $search_type, 'word' => $search_word];
      $result_from_tags = [];
      $result_res = [];

      // 検索ワードなしの場合、リターン
      if($search_word == ''){
        return view('searchpage.index', ['result' => 'no_result', 'old_info' => $old_info]);
      }

      // 検索処理
      switch ($search_type) {
        case 'plan':
          $result_type = 'plan';
          $search_result =  Plan::where('plan_title', 'like', "%$search_word%")->get();
          $this->getPlans($search_result);

          $tags_search_result = Tag::where('name', 'like', "%$search_word%")->get();
          $this->getPlans($tags_search_result);
          foreach($tags_search_result as $tag){
            array_push($result_from_tags, $tag->plans);
          }
          break;

        case 'spot':
          $result_type = 'spot';
          $search_result =  Spot::where('spot_title', 'like', "%$search_word%")->get();
          $this->getSpots($search_result);

          $tags_search_result = Tag::where('name', 'like', "%$search_word%")->get();
          $this->getSpots($tags_search_result);
          foreach($tags_search_result as $tag){
            array_push($result_from_tags, $tag->spots);
          }
          break;

        case 'user':
          $users = User::where('name', 'like', "%$search_word%")->get();
          $search_result = $this->getFollowInfo($users, Auth::id());
          break;
      }

      // 検索結果なしの場合NoResultでリターン,ユーザー検索の場合検索結果リターン
      if(count($search_result) == 0 && count($result_from_tags) == 0){
        return view('searchpage.index', ['result' => 'no_result', 'old_info' => $old_info]);
      }elseif ($search_type == 'user') {
        $response = $this->RemakeArray($search_result);
        return view('searchpage.index', ['result' => 'user', 'response' => $response, 'old_info' => $old_info]);
      }

      // 名称検索結果を配列に格納
      foreach ($search_result as $s_result) {
        array_push($result_res, $s_result);
      }

      // タグ検索結果を配列に格納
      foreach ($result_from_tags as $result_from_eachTag) {
        foreach ($result_from_eachTag as $t_result) {
            array_push($result_res, $t_result);
        }
      }

      // 名称検索、タグ検索結果の重複削除
      $response = $this->removeDuplication($result_res);
      $response = $this->RemakeArray($response);

      return view('searchpage.index', [
        'result' => $result_type,
        'response' => $response,
        'old_info' => $old_info
      ]);
    }


    public function RemakeArray($target_array)
    {
      $item_amount = 3;
      $items = [];
      $items[0] = [];
      $i = 0;

      foreach ($target_array as $index => $user) {
        $count = $index + 1;

        array_push($items[$i], $user);

        if($index + 1 == count($target_array)){
          break;
        }

        if($count % $item_amount == 0){
          $i++;
          $items[$i] = [];
        }
      }
      return $items;
    }


    public function planSearch(Request $request)
    {

      $search_transportation = $request->all()['transportation'];
      $search_duration = $request->all()['duration'];

      if($search_transportation != null && $search_duration != null){
        $search_result = Plan::where('main_transportation', '=', $search_transportation)->orwhere('plan_duration', '=', $search_duration)->get();
      }elseif ($search_duration != null) {
        $search_result = Plan::where('plan_duration', '=', $search_duration)->get();
      }elseif ($search_transportation != null){
        $search_result = Plan::where('main_transportation', '=', $search_transportation)->get();
      }

      $response = $this->removeDuplication($search_result);
      return view('searchpage.index', ['result' => 'plan', 'response' => $response]);
    }


    public function spotSearch(Request $request)
    {
      $search_stay = $request->all()['stay'];
      $search_address = $request->all()['address'];

      if($search_stay != null && $search_address != null){
        $search_result = Spot::where('spot_duration', '=', $search_stay)->orwhere('spot_address', 'like', "%$search_address%")->get();
      }elseif ($search_stay != null) {
        $search_result = Spot::where('spot_duration', '=', $search_stay)->get();
      }elseif ($search_address != null){
        $search_result = Spot::where('spot_address', 'like', "%$search_address%")->get();
      }

      $response = $this->removeDuplication($search_result);
      return view('searchpage.index', ['result' => 'spot', 'response' => $response]);
    }

    public function removeDuplication($array)
    {
      $response = [];

      foreach ($array as $item) {
        $flg = true;
        foreach ($response as $res_item) {
          if($item->id == $res_item->id){
            $flg = false;
            break;
          }
        }
        if($flg == true){
          array_push($response, $item);
        }
      }
      return $response;
    }


    public function getFollowInfo($users, $current_user_id)
    {
      foreach($users as $user){
        $user->plans;
        $user->followers;
        foreach ($user->followers as $follow) {
          if($follow->follower_user_id == $current_user_id){
            $user->follow_flg = true;
          }else{
            $user->follow_flg = false;
          }
        }
      }

      return $users;
    }

    public function getPlans($plans_all)
    {
      foreach ($plans_all as $index => $plan) {
        $count = $index + 1;
        $plan->spots;
        $plan->favs;
        $plan->tags;
        foreach ($plan->spots as $spot) {
          $spot->images;
        }
      }

    }

    public function getSpots($spots_all)
    {

      foreach ($spots_all as $index => $spot) {
        $count = $index + 1;
        $spot->images;
        $spot->favs;
        $spot->user;
      }

    }

}
