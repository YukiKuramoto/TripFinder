<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkLoginStatus($current_uid, $post_uid)
    {
      if($current_uid !== $post_uid){
        return false;
      }else{
        return true;
      }
    }


    public function multiDescSortArray($array, $key)
    {
      $sort_key = array_column($array, $key);
      array_multisort($sort_key, SORT_DESC, $array);
      return $array;
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


    public function RemakeArray($target_array, $item_amount)
    {
      // $item_amount = 6;
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

      return $plans_all;
    }

    public function getSpots($spots_all)
    {

      foreach ($spots_all as $index => $spot) {
        $count = $index + 1;
        $spot->images;
        $spot->favs;
        $spot->user;
        $spot->links;
      }
      return $spots_all;

    }


    public function getSpotsDetail($spot)
    {
      // スポット詳細情報取得
      $spot->tags;
      $spot->images;
      $spot->favs;
      $spot->user;
      $spot->links;
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


    public function decideFavStatus($favObject)
    {
      if(count($favObject) == 0){
        return false;
      }else{
        return true;
      };
    }

    /**
    * コメント一覧表示Vue.js用に関連情報取得function
    * 取得対象（ユーザー名、ユーザープロフィール画像パス）
    *
    * @param Object $spot   スポット情報Object
    * @return void
    */
    public function getCommentInfo($spot)
    {
      foreach($spot->comments as $item){
        $user = User::find($item->user_id);
        $item->user_name = $user->name;
        $item->user_image = $user->image_path;
      }
    }

    public function mergeTags($targetObject)
    {
      $tag_string = '';
      foreach($targetObject->tags as $tag){
        $tag_string = $tag_string . $tag->name . ",";
      }

      return $tag_string;
    }

}
