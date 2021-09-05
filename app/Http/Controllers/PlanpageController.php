<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;
use App\Spot;
use App\Tag;
use App\PlanTag;
use App\SpotTag;
use App\User;

class PlanpageController extends Controller
{
    public function index($user_id, $plan_id)
    {
      $plan = Plan::find($plan_id);
      $plan->tags;

      foreach($plan->spots as $spot){
        $spot->tags;
        $spot->images;
      }

      return view('planpage.index', ['plan' => $plan, 'spot' => $plan->spots]);
    }
}
