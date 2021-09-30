<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function search(Request $request)
    {
      $search_type = $request->all()['search_type'];
      $search_word = $request->all()['search_word'];
      $old_info = ['type' => $search_type, 'word' => $search_word];
      $result_from_tags = [];
      $result_res = [];
      $response = [];

      if($search_word == ''){
        return view('searchpage.index', ['result' => 'no_result', 'old_info' => $old_info]);
      }

      switch ($search_type) {
        case 'plan':
          $result_type = 'plan';
          $search_result =  Plan::where('plan_title', 'like', "%$search_word%")->get();
          $tags_search_result = Tag::where('name', 'like', "%$search_word%")->get();
          foreach($tags_search_result as $tag){
            array_push($result_from_tags, $tag->plans);
          }
          break;

        case 'spot':
          $result_type = 'spot';
          $search_result =  Spot::where('spot_title', 'like', "%$search_word%")->get();
          $tags_search_result = Tag::where('name', 'like', "%$search_word%")->get();
          foreach($tags_search_result as $tag){
            array_push($result_from_tags, $tag->spots);
          }
          break;

        case 'user':
          $users = User::where('name', 'like', "%$search_word%")->get();
          if(count($users) == 0){
            return view('searchpage.index', ['result' => 'no_result', 'old_info' => $old_info]);
          }
          return view('searchpage.index', ['result' => 'user', 'response' => $users, 'old_info' => $old_info]);
          break;
      }

      if(count($search_result) == 0 && count($result_from_tags) == 0){
        return view('searchpage.index', ['result' => 'no_result', 'old_info' => $old_info]);
      }

      foreach ($search_result as $s_result) {
        array_push($result_res, $s_result);
      }
      foreach ($result_from_tags as $result_from_eachTag) {
        foreach ($result_from_eachTag as $t_result) {
            array_push($result_res, $t_result);
        }
      }

      foreach ($result_res as $item) {
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

      return view('searchpage.index', ['result' => $result_type, 'response' => $response, 'old_info' => $old_info]);

    }
}
