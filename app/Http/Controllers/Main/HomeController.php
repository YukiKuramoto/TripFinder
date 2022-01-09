<?php

namespace App\Http\Controllers\Main;

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

        $popularPlans = Plan::withCount('favs')->orderBy('favs_count', 'desc')->take(3)->get();
        $this->getPlans($popularPlans);
        $popularPlans = $this->RemakeArray($popularPlans,3);

        $popularSpots = Spot::withCount('favs')->orderBy('favs_count', 'desc')->take(4)->get();
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

}
