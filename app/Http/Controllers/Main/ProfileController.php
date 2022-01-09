<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use Storage;

class ProfileController extends Controller
{

    public function edit()
    {
      $user_id = Auth::id();
      $user = User::find($user_id);
      return view('profile.index', ['user' => $user]);
    }


    public function update(Request $request)
    {
      $user_request = $request->all();
      $current_uid = Auth::id();

      // dd($current_uid == $user_request['user_id']);
      // dd($user_request['user_id']);

      // ユーザー確認
      if($current_uid != $user_request['user_id']){
        return redirect()->action('Main\MypageController@index', ['user_id' => $current_uid]);
      }

      unset($user_request['_token']);
      $user = User::find($user_request['user_id']);

      if (array_key_exists('image_path',$user_request)) {
        $image_path = $user_request['image_path'];
        $path = Storage::disk('s3')->putFile('/', $image_path, 'public');
        $user->image_path = Storage::disk('s3')->url($path);
      }

      $user->name = $user_request['name'];
      $user->comment = $user_request['comment'];
      $user->save();

      return redirect()->action('Main\MypageController@index', ['user_id' => $user->id]);
    }
}
