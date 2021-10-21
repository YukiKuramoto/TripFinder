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
      // 検索ワード、検索タイプの切り出し
      $search_word = $request->all()['search_word'];
      $search_type = $request->all()['search_type'];


      // 検索ワードなしの場合、リターン
      if($search_word == ''){
        return view('searchpage.index', ['result' => 'no_result']);
      }

      // 検索実行
      $response = $this->SearchFromDB_Main($search_word, $search_type);
      // dd($response);

      // 検索結果なしの場合、リターン
      if(count($response) == 0 || $response == []) {
        return view('searchpage.index', ['result' => 'no_result']);
      }

      // 結果ありの場合、結果をリターン
      return view('searchpage.index', [
        'result' => $search_type,
        'response' => $response,
        'search_word' => $search_word,
        'parameter' => 'main',
      ]);
    }


    public function planSearch(Request $request)
    {
      // 検索ワードの切り出し
      $search_transportation = $request->all()['transportation'];
      $search_duration = $request->all()['duration'];
      $search_address = $request->all()['address'];
      $search_word = ['transportation' => $search_transportation, 'duration' => $search_duration, 'address' => $search_address];

      // 検索実行
      $response = $this->SearchFromDB_Plan($search_transportation, $search_duration, $search_address);

      // 検索結果なしの場合、リターン
      if($response == null){
        return view('searchpage.index', ['result' => 'no_result']);
      }

      // 検索結果リターン
      return view('searchpage.index', [
        'result' => 'plan',
        'response' => $response,
        'search_word' => $search_word,
        'parameter' => 'keytag',
      ]);
    }


    public function spotSearch(Request $request)
    {
      // 検索ワードの切り出し
      $search_stay = $request->all()['stay'];
      $search_address = $request->all()['address'];
      $search_word = ['stay' => $search_stay, 'address' => $search_address];

      // 検索実行
      $response = $this->SearchFromDB_Spot($search_stay, $search_address);

      // 検索結果なしの場合、リターン
      if($response == null){
        return view('searchpage.index', ['result' => 'no_result']);
      }

      // 検索結果リターン
      return view('searchpage.index', [
        'result' => 'spot',
        'response' => $response,
        'search_word' => $search_word,
        'parameter' => 'keytag',
      ]);
    }


    public function index_nextplan(Request $request)
    {
      $request_form = $request->all();

      switch ($request_form['parameter']) {
        case 'main':
          $page_index = $request->all()['page'];
          $response = $this->SearchFromDB_Main($request_form['search_word'], 'plan');
          return ([
            'user' => Auth::user(),
            'plans' => $response[$page_index - 1],
            'plan_length' => count($response),
            'plan_current' => $page_index,
          ]);
          break;

        case 'keytag':
          $page_index = $request->all()['page'];
          $search_transportation = $request_form['search_word']['transportation'];
          $search_duration = $request_form['search_word']['duration'];
          $search_address = $request_form['search_word']['address'];

          $response = $this->SearchFromDB_Plan($search_transportation, $search_duration, $search_address);
          return ([
            'user' => Auth::user(),
            'plans' => $response[$page_index - 1],
            'plan_length' => count($response),
            'plan_current' => $page_index,
          ]);
          break;
      }
    }


    public function index_nextspot(Request $request)
    {
      $request_form = $request->all();

      switch ($request_form['parameter']) {
        case 'main':
          $page_index = $request->all()['page'];
          $response = $this->SearchFromDB_Main($request_form['search_word'], 'spot');
          // dd($response[$page_index - 1]);
          return ([
            'user' => Auth::user(),
            'spots' => $response[$page_index - 1],
            'spot_length' => count($response),
            'spot_current' => $page_index,
          ]);
          break;

        case 'keytag':
          $page_index = $request->all()['page'];
          $search_stay = $request_form['search_word']['stay'];
          $search_address = $request_form['search_word']['address'];

          $response = $this->SearchFromDB_Spot($search_stay, $search_address);
          return ([
            'user' => Auth::user(),
            'spots' => $response[$page_index - 1],
            'spot_length' => count($response),
            'spot_current' => $page_index,
          ]);
          break;
      }
    }


    public function index_nextuser(Request $request)
    {
      $request_form = $request->all();

      $page_index = $request->all()['page'];
      $response = $this->SearchFromDB_Main($request_form['search_word'], 'user');

      return ([
        'users' => $response[$page_index - 1],
        'users_length' => count($response),
      ]);
    }



//*********************************  DB　検索関連  ************************************


    public function SearchFromDB_Main($search_word, $search_type)
    {
      $result_from_tags = [];
      $result_res = [];
      $response = [];

      // 検索処理
      switch ($search_type) {
        case 'plan':
          $search_result = Plan::where('plan_title', 'like', "%$search_word%")->get();
          $this->getPlans($search_result);

          $tags_search_result = Tag::where('name', 'like', "%$search_word%")->get();
          foreach($tags_search_result as $tag){
            $this->getPlans($tag->plans);
            array_push($result_from_tags, $tag->plans);
          }
          break;

        case 'spot':
          $search_result = Spot::where('spot_title', 'like', "%$search_word%")->get();
          $this->getSpots($search_result);

          $tags_search_result = Tag::where('name', 'like', "%$search_word%")->get();
          foreach($tags_search_result as $tag){
            $this->getSpots($tag->spots);
            array_push($result_from_tags, $tag->spots);
          }
          // dd($tags_search_result);
          break;

        case 'user':
          $users = User::where('name', 'like', "%$search_word%")->get();
          $response = $this->getFollowInfo($users, Auth::id());
          $response = $this->RemakeArray($response);
          return $response;
          break;
      }

      // dd(method_exists($this,'SearchFromDB_Spot'));

      // 検索結果なしの場合NoResultでリターン,ユーザー検索の場合検索結果リターン
      if(count($search_result) == 0 && count($result_from_tags) == 0){
        return $response;
      }

      if ($search_type != 'user') {
        // 名称検索結果を配列に格納
        foreach ($search_result as $s_result) {
          $s_result->getAllRelation;
          array_push($result_res, $s_result);
        }

        // タグ検索結果を配列に格納
        foreach ($result_from_tags as $result_from_eachTag) {
          foreach ($result_from_eachTag as $t_result) {
            $t_result->getAllRelation;
            array_push($result_res, $t_result);
          }
        }

        // 名称検索、タグ検索結果の重複削除
        $response = $this->removeDuplication($result_res);
      }

      $response = $this->RemakeArray($response);
      return $response;
    }


    public function SearchFromDB_Plan($search_transportation, $search_duration, $search_address)
    {

      if($search_address != null){
        $search_result = Plan::where('main_transportation', '=', $search_transportation)
        ->orwhere('plan_duration', '=', $search_duration)
        ->orwhereHas('spots', function($query) use($search_address){
          $query->where('spot_address', 'like', "%$search_address%");
        })->with('spots')->with('images')->with('favs')->with('tags')->get();
      }else{
        $search_result = Plan::where('main_transportation', '=', $search_transportation)
        ->orwhere('plan_duration', '=', $search_duration)
        ->get();
      }

      if (count($search_result) == 0){
        return;
      }


      $response = $this->removeDuplication($search_result);
      // $this->getPlans($response);
      $response = $this->RemakeArray($response);

      return $response;
    }




    public function SearchFromDB_Spot($search_stay, $search_address)
    {

      if($search_stay != null && $search_address != null){
        $search_result = Spot::where('spot_duration', '=', $search_stay)->orwhere('spot_address', 'like', "%$search_address%")->get();
      }elseif ($search_stay != null) {
        $search_result = Spot::where('spot_duration', '=', $search_stay)->get();
      }elseif ($search_address != null){
        $search_result = Spot::where('spot_address', 'like', "%$search_address%")->get();
      }else{
        return;
      }

      if (count($search_result) == 0){
        return;
      }

      $response = $this->removeDuplication($search_result);
      $this->getSpots($response);
      $response = $this->RemakeArray($response);

      return $response;
    }




//*********************************  その他 Function  ************************************


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


    public function RemakeArray($target_array)
    {
      $item_amount = 6;
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
