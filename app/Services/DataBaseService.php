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
      // dd($req);

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
        $this->array_push_eachItem($res, $item);
      }

      return $res;
    }



    public function SearchFromDB_Spot($req)
    {
      $res = [];
      $result_spotDB = [];
      $result_tagDB = [];
      // dd($req);

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
      // dd($query->toSql());

      if(isset($req['search_word']) && $req['search_word'] != null){
        $query = Tag::query();
        $word = $req['search_word'];
        $query->where('name', 'like', "%$word%");
        $result_tagDB = $query->get();
      }


      $res = $this->array_push_eachItem($res, $result_spotDB);
      foreach($result_tagDB as $item){
        $this->array_push_eachItem($res, $item);
      }

      return $res;
    }



      public function SearchFromDB_User($req)
      {
        $res = [];
        $result_userDB = [];
        $current_uid = Auth::id();
        // dd($current_uid);
        // dd($req);

        $query = User::query();
        if(isset($req['search_word']) && $req['search_word'] != null){
          $word = $req['search_word'];
          $query->where('name', 'like', "%$word%");
        }
        $query->where('id', '<>', $current_uid);
        $res = $query->get();
        // dd($res);
        // dd($query->toSql());

        return $res;
      }

      public function array_push_eachItem($array, $target)
      {
        foreach($target as $item){
          array_push($array, $item);
        }

        return $array;
      }
}
