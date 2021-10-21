<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Spot;

class HomeController extends Controller
{
    public function index()
    {

        $newArrivalPlans = Plan::latest()->take(3)->get();
        $this->getPlans($newArrivalPlans);
        $newArrivalPlans = $this->RemakeArray($newArrivalPlans,3);

        $popularPlans = Plan::withCount('favs')->take(3)->get();
        $this->getPlans($popularPlans);
        $popularPlans = $this->RemakeArray($popularPlans,3);

        $popularSpots = Spot::withCount('favs')->take(4)->get();
        $this->getSpots($popularSpots);
        $popularSpots = $this->RemakeArray($popularSpots, 4);


        return view('home.index', [
          "newArrivalPlans" => $newArrivalPlans,
          "popularPlans" => $popularPlans,
          "popularSpots" => $popularSpots,
          "param_newArrival" => "newArrival",
          "param_popular" => "popular",
        ]);
    }





    public function RemakeArray($target_array, $item_amount)
    {
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

}

public function getSpots($spots_all)
{

  foreach ($spots_all as $index => $spot) {
    $count = $index + 1;
    $spot->images;
    $spot->favs;
    $spot->user;
  }

}

}
