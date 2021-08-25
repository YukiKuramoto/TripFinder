<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Plan;
use App\Spot;
use App\Tag;
use App\PlanTag;
use App\SpotTag;
use App\User;

class MypageController extends Controller
{
    public function index()
    {
      $uid = Auth::id();
      $user = User::find($uid);
      // dd($user->plans[0]->spots[0]->spot_image);
      return view('mypage.index', ['user' => $user]);
    }

    public function showpost ($user_id, $plan_id)
    {
      dd('user_id' . $user_id . ', plan_id' . $plan_id);
    }
}
