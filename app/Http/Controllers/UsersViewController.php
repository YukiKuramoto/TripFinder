<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Follow;

class UsersViewController extends Controller
{
    public function index()
    {
      // ログインユーザー取得
      $current_user_id = Auth::id();
      $current_user = User::find($current_user_id);
      $favorite_users = [];
      $follower_users = [];


      // 全ユーザ取得
      $users = User::all();
      $this->getFollowInfo($users, $current_user_id);
      $users_pagenated = $this->RemakeArray($users);

      // Favoriteユーザー取得
      foreach($current_user->follows as $favorite_user){
        array_push($favorite_users, User::find($favorite_user->followed_user_id));
      }
      $this->getFollowInfo($favorite_users, $current_user_id);
      $favorite_users_pagenated = $this->RemakeArray($favorite_users);


      //Followerユーザー取得
      foreach($current_user->followers as $follower_user){
        array_push($follower_users, User::find($follower_user->follower_user_id));
      }
      $this->getFollowInfo($follower_users, $current_user_id);
      $follower_users_pagenated = $this->RemakeArray($follower_users);


      return view('users.index', [
        'users' => $users_pagenated,
        'favorite_users' => $favorite_users_pagenated,
        'follower_users' => $follower_users_pagenated
      ]);
    }


    public function RemakeArray($user_arr)
    {
      $item_amount = 3;
      $items = [];
      $items[0] = [];
      $i = 0;

      foreach ($user_arr as $index => $user) {
        $count = $index + 1;

        array_push($items[$i], $user);

        if($index + 1 == count($user_arr)){
          break;
        }

        if($count % $item_amount == 0){
          $i++;
          $items[$i] = [];
        }
      }
      return $items;
    }


    public function index_nextuser(Request $request)
    {
      // ログインユーザー取得
      $current_user_id = Auth::id();
      $current_user = User::find($current_user_id);
      //パラメータ取得
      $parameter = $request->all()['parameter'];
      $target_users = [];
      $page_index = $request->all()['page'];


      //パラメータによって表示対象配列を制御
      switch ($parameter) {
        case 'all':
          $target_users = User::all();
          break;

        case 'favorite':
          foreach($current_user->follows as $favorite_user){
            array_push($target_users, User::find($favorite_user->followed_user_id));
          }
          break;

        case 'follower':
          foreach($current_user->followers as $follower_user){
            array_push($target_users, User::find($follower_user->follower_user_id));
          }
          break;
      }

      // フォロー状況取得
      $this->getFollowInfo($target_users, $current_user_id);
      // ページネーション用の形式に変換
      $users_pagenated = $this->RemakeArray($target_users);

      return ([
        'users' => $users_pagenated[$page_index - 1],
        'users_length' => count($users_pagenated),
        'users_current' => $page_index,
      ]);
    }


    public function follow($target_user_id)
    {
      $user_id = Auth::id();
      $follow = new Follow;
      $follow->followed_user_id = $target_user_id;
      $follow->follower_user_id = $user_id;
      $follow->save();
      return redirect('/users/index');
    }


    public function unfollow($target_user_id)
    {
      $user_id = Auth::id();
      $follow_info = Follow::where('followed_user_id', $target_user_id)->where('follower_user_id', $user_id)->delete();
      return redirect('/users/index');
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
    }
}
