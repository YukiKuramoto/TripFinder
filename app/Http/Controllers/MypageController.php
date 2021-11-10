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
    public function index($user_id)
    {
      $user = User::find($user_id);
      $login_uid = Auth::id() != [] ? Auth::id() : 'undefined_user';
      $plans = $this->getPlans($user->plans);
      $plans_fav = $this->getPlans($user->favPlans);
      $spots_fav = $this->getSpots($user->favSpots);
      $plans = $this->RemakeArray($plans, 3);
      $plans_fav = $this->RemakeArray($plans_fav, 3);
      $spots_fav = $this->RemakeArray($spots_fav, 4);

      return view('mypage.index', [
        'login_uid' => $login_uid,
        'postuser' => $user,
        'plans' => $plans,
        'plans_fav' => $plans_fav,
        'spots_fav' => $spots_fav,
      ]);
    }



    public function index_nextplan(Request $request)
    {
      $type = $request->all()['parameter'];
      $postuser = $request->all()['postuser'];
      $user = User::find($postuser['id']);

      switch ($type) {
        case 'myplan':
          $plans = $this->getPlans($user->plans);
          break;

        case 'favplan':
          $plans = $this->getPlans($user->favPlans);
          break;
      }
      $plans = $this->RemakeArray($plans, 3);

      return ([
        'postuser' => $user,
        'response' => $plans[$request->all()['page'] - 1],
        'response_length' => count($plans),
      ]);
    }


    public function index_nextspot(Request $request)
    {
      $postuser = $request->all()['postuser'];
      $user = User::find($postuser['id']);
      $spots = $this->getSpots($user->favSpots);
      $spots = $this->RemakeArray($spots, 4);

      return ([
        'postuser' => $user,
        'response' => $spots[$request->all()['page'] - 1],
        'response_length' => count($spots),
      ]);
    }
}
