<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DataBaseService;
use App\Plan;
use App\User;
use App\Spot;
use App\Tag;

class SearchController extends Controller
{
    private $DataBaseService;
    public function __construct(DataBaseService $DB_service)
    {
        $this->DataBaseService = $DB_service;
    }


    public function index()
    {
      return view('searchpage.index');
    }

    public function mainSearch(Request $request)
    {
      switch ($request->all()['search_type']) {
        case 'plan':
          $result = $this->planSearch($request);
          return view('searchpage.index', $result);
          break;

        case 'spot':
          $result = $this->spotSearch($request);
          return view('searchpage.index', $result);
          break;

        case 'user':
          $result = $this->userSearch($request);
          return view('searchpage.index', $result);
          break;
      }
    }


    public function planSearch(Request $request)
    {
      $request_form = $request->all();
      $search_key = [];

      if(isset($request_form['search_word'])){
        $search_key['search_word'] = $request_form['search_word'];
      }
      if(isset($request_form['transportation'])){
        $search_key['transportation'] = $request_form['transportation'];
      }
      if(isset($request_form['duration'])){
        $search_key['duration'] = $request_form['duration'];
      }
      if(isset($request_form['address'])){
        $search_key['address'] = $request_form['address'];
      }

      $response = $this->DataBaseService->SearchFromDB_Plan($search_key);
      $response = $this->removeDuplication($response);
      $response = $this->getPlans($response);
      $response = $this->RemakeArray($response, 6);

      // 検索結果なしの場合、リターン
      if(count($response) == 0 || $response[0] == []) {
        return (['result' => 'no_result']);
      }elseif (isset($request_form['parameter'])) {
        return ([
          'response' => $response[$request->all()['page'] - 1],
          'response_length' => count($response),
        ]);
      }else{
        return ([
          'result' => 'plan',
          'response' => $response,
          'search_key' => $search_key,
          'parameter' => 'plan',
        ]);
      }
    }


    public function spotSearch(Request $request)
    {
      $request_form = $request->all();
      $search_key = [];

      if(isset($request_form['search_word'])){
        $search_key['search_word'] = $request_form['search_word'];
      }
      if(isset($request_form['stay'])){
        $search_key['stay'] = $request_form['stay'];
      }
      if(isset($request_form['address'])){
        $search_key['address'] = $request_form['address'];
      }


      $response = $this->DataBaseService->SearchFromDB_Spot($search_key);
      $response = $this->removeDuplication($response);
      $response = $this->getSpots($response);
      $response = $this->RemakeArray($response, 6);


      // 検索結果なしの場合、リターン
      if(count($response) == 0 || $response[0] == []) {
        return (['result' => 'no_result']);
      }elseif (isset($request_form['parameter'])) {
        return ([
          'response' => $response[$request->all()['page'] - 1],
          'response_length' => count($response),
        ]);
      }else{
        return ([
          'result' => 'spot',
          'response' => $response,
          'search_key' => $search_key,
          'parameter' => 'spot',
        ]);
      }
    }



    public function userSearch(Request $request)
    {
      $request_form = $request->all();
      $search_key = [];
      if(Auth::user()){
        $login_user = Auth::user();
      }else{
        $login_user = 'undefined_user';
      }

      if(isset($request_form['search_word'])){
        $search_key['search_word'] = $request_form['search_word'];
      }

      $response = $this->DataBaseService->SearchFromDB_User($search_key);
      $response = $this->getFollowInfo($response, Auth::id());
      $response = $this->RemakeArray($response, 3);


      // 検索結果なしの場合、リターン
      if(count($response) == 0 || $response[0] == []) {
        return (['result' => 'no_result']);
      }elseif (isset($request_form['parameter'])) {
        return ([
          'response' => $response[$request->all()['page'] - 1],
          'response_length' => count($response),
        ]);
      }else{
        return ([
          'login_user' => $login_user,
          'result' => 'user',
          'response' => $response,
          'search_key' => $search_key,
          'parameter' => 'main',
        ]);
      }
    }



}
