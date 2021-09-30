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

      // Favoriteユーザー取得
      foreach($current_user->follows as $favorite_user){
        array_push($favorite_users, User::find($favorite_user->followed_user_id));
      }
      $this->getFollowInfo($favorite_users, $current_user_id);

      //Followerユーザー取得
      foreach($current_user->followers as $follower_user){
        array_push($follower_users, User::find($follower_user->follower_user_id));
      }
      $this->getFollowInfo($follower_users, $current_user_id);

      return view('users.index', ['users' => $users, 'favorite_users' => $favorite_users, 'follower_users' => $follower_users]);
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
