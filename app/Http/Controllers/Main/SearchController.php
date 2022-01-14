<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DBSearchService;
use App\Http\Controllers\Controller;
use App\Plan;
use App\User;
use App\Spot;
use App\Tag;

class SearchController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Search Controller
  |--------------------------------------------------------------------------
  |
  | リクエスト内の検索ワードに基づき検索処理を行うコントローラー
  | 検索処理実行はDBSearchService内に定義したFunctionによって実行
  | index       : 検索画面用ビュー表示
  | homeSearch  : ホーム画面から検索時のプラン処理
  | planSearch  : 検索画面から検索時のプラン検索処理
  | spotSearch  : 検索画面から検索時のスポット検索処理
  | userSearch  : 検索画面から検索時のユーザー検索処理
  |
  */

    // ホーム画面表示プラン・スポット・ユーザー数
    const planViewNum = 6;
    const spotViewNum = 6;
    const userViewNum = 3;
    private $DBSearchService;

    public function __construct(DBSearchService $DB_service)
    {
        $this->DBSearchService = $DB_service;
    }


    /**
    * コメント投稿用ビュー表示用function
    *
    * @return Illuminate\Contracts\Support\Renderable      検索用ビュー
    */
    public function index()
    {
      // Vue.jsで検索実行されない用空の配列を渡す
      $search_key = [];
      return view('searchpage.index', ['search_key' => $search_key]);
    }


    /**
    * ホーム画面から検索実行function
    *
    * @param Illuminate\Http\Request $request              Httpリクエスト
    * @return Illuminate\Contracts\Support\Renderable      検索結果表示用ビュー
    */
    public function homeSearch(Request $request)
    {
      // ホーム画面から送信された検索ワードをセット
      $search_key = $request->all()['search_key'];
      return view('searchpage.index', ['search_key' => $search_key]);

    }


    /**
    * 検索画面よりプラン検索実行function
    *
    * @param Illuminate\Http\Request $request              Httpリクエスト
    * @return array $response                              検索結果配列
    */
    public function planSearch(Request $request)
    {
      $request_form = $request->all();
      $search_key = [];

      // 検索キーワード取得処理
      $search_key = $this->DBSearchService->getSearchKey($request_form['search_key'], 'plan');
      // 取得したキーワードを元にDB検索実行
      $response = $this->DBSearchService->SearchFromDB_Plan($search_key);
      // dd($response);
      // 検索結果から重複データを削除実行
      $response = $this->removeDuplication($response);
      // 検索結果を保存最新順にソート
      $response = $this->multiDescSortArray($response, 'updated_at');

      // Vue.js表示用に関連情報を取得
      $response = $this->getPlans($response);

      // ページネーション表示用に配列編集
      $response = $this->RemakeArray($response, self::planViewNum);
      // リターン用レスポンスを作成
      $response = $this->DBSearchService->completeResponse($request_form, $response, $search_key, 'plan');

      return $response;

    }


    /**
    * 検索画面よりスポット検索実行function
    *
    * @param Illuminate\Http\Request $request              Httpリクエスト
    * @return array $response                              検索結果配列
    */
    public function spotSearch(Request $request)
    {
      $request_form = $request->all();
      $search_key = [];

      // 検索キーワード取得処理
      $search_key = $this->DBSearchService->getSearchKey($request_form['search_key'], 'spot');
      // 取得したキーワードを元にDB検索実行
      $response = $this->DBSearchService->SearchFromDB_Spot($search_key);
      // 検索結果から重複データを削除実行
      $response = $this->removeDuplication($response);
      // 検索結果を保存最新順にソート
      $response = $this->multiDescSortArray($response, 'updated_at');

      // Vue.js表示用に関連情報を取得
      $response = $this->getSpots($response);
      // ページネーション表示用に配列編集
      $response = $this->RemakeArray($response, self::spotViewNum);
      // リターン用レスポンスを作成
      $response = $this->DBSearchService->completeResponse($request, $response, $search_key, 'spot');

      return $response;

    }


    /**
    * 検索画面よりユーザー検索実行function
    *
    * @param Illuminate\Http\Request $request              Httpリクエスト
    * @return array $response                              検索結果配列
    */
    public function userSearch(Request $request)
    {
      $request_form = $request->all();
      $login_user = Auth::user() == null ? 'undefined_user' : Auth::user();
      $search_key = [];

      // 検索キーワード取得処理
      $search_key = $this->DBSearchService->getSearchKey($request_form['search_key'], 'user');
      // 検索実行処理
      $response = $this->DBSearchService->SearchFromDB_User($search_key);

      // Vue.js表示用に関連情報を取得
      $response = $this->getFollowInfo($response, Auth::id());
      // ページネーション表示用に配列編集
      $response = $this->RemakeArray($response, self::userViewNum);
      // リターン用レスポンスを作成
      $response = $this->DBSearchService->completeResponse($request, $response, $search_key, 'user');

      return $response;
    }

}
