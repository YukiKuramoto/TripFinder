<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Plan;
use App\User;
use App\Spot;
use App\Tag;

class DataBaseService
{
    public function SearchFromDB_Plan($req)
    {
      $res = [];
      $result_planDB = [];
      $result_tagDB = [];

      $query = Plan::query();

      if(isset($req['search_word']) && $req['search_word'] != null){
        $word = $req['search_word'];
        $query->where('plan_title', 'like', "%$word%");
      }

      if(isset($req['transportation']) && $req['transportation'] != null){
        $query->where('main_transportation', '=', $req['transportation']);
      }

      if(isset($req['duration']) && $req['duration'] != null){
        $query->where('plan_duration', '=', $req['duration']);
      }

      if(isset($req['address']) && $req['address'] != null){
        $word = $req['address'];
        $query->whereHas('spots', function($q) use($word){
          $q->where('spot_address', 'like', "%$word%");
        });
      }

      $result_planDB = $query->get();

      if(isset($req['search_word']) && $req['search_word'] != null){
        $query = Tag::query();
        $word = $req['search_word'];
        $query->where('name', 'like', "%$word%");
        $result_tagDB = $query->get();
      }

      $res = $this->array_push_eachItem($res, $result_planDB);

      foreach($result_tagDB as $item){
        $res = $this->array_push_eachItem($res, $item->plans);
      }

      return $res;
    }


    public function SearchFromDB_Spot($req)
    {
      $res = [];
      $result_spotDB = [];
      $result_tagDB = [];

      $query = Spot::query();

      if(isset($req['search_word']) && $req['search_word'] != null){
        $word = $req['search_word'];
        $query->where('spot_title', 'like', "%$word%");
      }

      if(isset($req['stay']) && $req['stay'] != null){
        $query->where('spot_duration', '=', $req['stay']);
      }

      if(isset($req['address']) && $req['address'] != null){
        $word = $req['address'];
        $query->where('spot_address', 'like', "%$word%");
      }

      $result_spotDB = $query->get();

      if(isset($req['search_word']) && $req['search_word'] != null){
        $query = Tag::query();
        $word = $req['search_word'];
        $query->where('name', 'like', "%$word%");
        $result_tagDB = $query->get();
      }

      $res = $this->array_push_eachItem($res, $result_spotDB);

      foreach($result_tagDB as $item){
        $this->array_push_eachItem($res, $item->spots);
      }

      return $res;
    }


    public function SearchFromDB_User($req)
    {
      $res = [];
      $result_userDB = [];
      $current_uid = Auth::id();

      $query = User::query();

      if(isset($req['search_word']) && $req['search_word'] != null){
        $word = $req['search_word'];
        $query->where('name', 'like', "%$word%");
      }

      $query->where('id', '<>', $current_uid);
      $res = $query->get();

      return $res;
    }


    public function getSearchKey($request_form, $type)
    {
      $search_key = [];

      switch ($type) {
        case 'plan':
          // 検索キーワードの取得処理（フリーワード / 交通手段 / 所用日数 / 住所）
          if(isset($request_form['search_word'])){
            $search_key['search_word'] = $request_form['search_word'];
          }
          if(isset($request_form['transportation'])){
            $search_key['transportation'] = $request_form['transportation'];
          }
          if(isset($request_form['duration'])){
            $search_key['duration'] = $request_form['duration'];
          }
          if(isset($request_form['address'])){
            $search_key['address'] = $request_form['address'];
          }
          break;

        case 'spot':
        // 検索キーワードの取得処理（フリーワード / 滞在時間 / 住所）
          if(isset($request_form['search_word'])){
            $search_key['search_word'] = $request_form['search_word'];
          }
          if(isset($request_form['stay'])){
            $search_key['stay'] = $request_form['stay'];
          }
          if(isset($request_form['address'])){
            $search_key['address'] = $request_form['address'];
          }
          break;

        case 'user':
          // 検索キーワードの取得処理（フリーワード）
          if(isset($request_form['search_word'])){
            $search_key['search_word'] = $request_form['search_word'];
          }
          break;
      }

      return $search_key;
    }

    public function completeResponse($request_form, $response, $search_key, $type)
    {

      // 検索結果なしの場合、リターン
      if(count($response) == 0 || $response[0] == []) {
        return ([
          'result' => 'no_result',
          'search_key' => $search_key,
        ]);
      // 非同期処理時のリターン
      }else{
        return ([
          'response' => $response[$request_form['page'] - 1],
          'response_length' => count($response),
          'search_key' => $search_key,
          'result' => $type,
          'parameter' => $type,
        ]);
      }
    }


    public function array_push_eachItem($array, $target)
    {
      foreach($target as $item){
        array_push($array, $item);
      }

      return $array;
    }
}
