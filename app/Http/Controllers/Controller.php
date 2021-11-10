<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;





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
          // if(count($plans_all) == 0){
          //   return [];
          // }

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
          }
          return $spots_all;

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
}
