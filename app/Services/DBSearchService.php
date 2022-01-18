<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Plan;
use App\User;
use App\Spot;
use App\Tag;

class DBSearchService
{
  /*
  |--------------------------------------------------------------------------
  | DBSearch Service
  |--------------------------------------------------------------------------
  |
  | データベース検索用ビジネスロジック
  |
  | Httpリクエストからの検索キーワードの抽出、クエリの作成、検索実行を行う
  |
  | SearchFromDB_Plan  : Httpリクエスト内の検索キーからプランを検索
  | SearchFromDB_Spot  : Httpリクエスト内の検索キーからスポットを検索
  | SearchFromDB_User  : Httpリクエスト内の検索キーからユーザーを検索
  | getSearchKey  　　　: Httpリクエストから検索キーを抽出
  | completeResponse　　: Viewへのレスポンスを作成
  |
  */


    /**
    * Httpリクエスト内の検索キーを元に、DBからプランを検索するfunction
    *
    * @return array $req                    検索結果データを格納した配列
    */
    public function SearchFromDB_Plan($req)
    {
      // return用に空の配列を作成
      $res = [];
      $result_planDB = [];
      $result_tagDB = [];

      // ***** プランテーブル直接検索用のクエリ作成・実行 *****

      // クエリ用オブジェクト作成
      $query = Plan::query();

      // 検索ワードをクエリに追加（検索ワード部分一致）
      if(isset($req['search_word']) && $req['search_word'] != null){
        $word = $req['search_word'];
        $query->where('plan_title', 'like', "%$word%");
      }

      // 主要交通手段をクエリに追加（主要交通手段完全一致）
      if(isset($req['transportation']) && $req['transportation'] != null){
        $query->where('main_transportation', '=', $req['transportation']);
      }

      // 必要日数をクエリに追加（日数完全一致）
      if(isset($req['duration']) && $req['duration'] != null){
        $query->where('plan_duration', '=', $req['duration']);
      }

      // 住所をクエリに追加（住所部分一致）
      if(isset($req['address']) && $req['address'] != null){
        $word = $req['address'];
        $query->whereHas('spots', function($q) use($word){
          $q->where('spot_address', 'like', "%$word%");
        });
      }

      // 作成したクエリを実行
      $result_planDB = $query->get();
      // 検索結果をリターン用配列に追加
      $res = $this->array_push_eachItem($res, $result_planDB);

      // ***** プランタグから検索用のクエリ作成・実行 *****


      //プランタグワードをクエリに追加 （検索ワード部分一致）
      if(isset($req['search_word']) && $req['search_word'] != null){
        // クエリ用オブジェクト作成
        $query = Tag::query();
        $word = $req['search_word'];
        $query->where('name', 'like', "%$word%");
        // 作成したクエリを実行
        $result_tagDB = $query->get();
      }


      // 検索結果をリターン用配列に追加
      foreach($result_tagDB as $item){
        $res = $this->array_push_eachItem($res, $item->plans);
      }

      // 検索結果をリターン
      return $res;
    }


    /**
    * Httpリクエスト内の検索キーを元に、DBからスポットを検索するfunction
    *
    * @return array $req                    検索結果データを格納した配列
    */
    public function SearchFromDB_Spot($req)
    {
      // return用に空の配列を作成
      $res = [];
      $result_spotDB = [];
      $result_tagDB = [];

      // ***** スポットテーブル直接検索用のクエリ作成・実行 *****

      // クエリ用オブジェクト作成
      $query = Spot::query();

      // 検索ワードをクエリに追加（検索ワード部分一致）
      if(isset($req['search_word']) && $req['search_word'] != null){
        $word = $req['search_word'];
        $query->where('spot_title', 'like', "%$word%");
      }

      // 滞在時間をクエリに追加（滞在時間完全一致）
      if(isset($req['stay']) && $req['stay'] != null){
        $query->where('spot_duration', '=', $req['stay']);
      }

      // 住所をクエリに追加（住所部分一致）
      if(isset($req['address']) && $req['address'] != null){
        $word = $req['address'];
        $query->where('spot_address', 'like', "%$word%");
      }

      // 作成したクエリを実行
      $result_spotDB = $query->get();
      // 検索結果をリターン用配列に追加
      $res = $this->array_push_eachItem($res, $result_spotDB);

      // ***** スポットタグから検索用のクエリ作成・実行 *****


      //スポットタグワードをクエリに追加 （検索ワード部分一致）
      if(isset($req['search_word']) && $req['search_word'] != null){
        // クエリ用オブジェクト作成
        $query = Tag::query();
        $word = $req['search_word'];
        $query->where('name', 'like', "%$word%");
        // 作成したクエリを実行
        $result_tagDB = $query->get();
      }

      // 検索結果をリターン用配列に追加
      foreach($result_tagDB as $item){
        $this->array_push_eachItem($res, $item->spots);
      }

      // 検索結果をリターン
      return $res;
    }


    /**
    * Httpリクエスト内の検索キーを元に、DBからユーザーを検索するfunction
    *
    * @return array $req                    検索結果データを格納した配列
    */
    public function SearchFromDB_User($req)
    {
      $res = [];
      $result_userDB = [];
      $current_uid = Auth::id();

      // クエリ用オブジェクト作成
      $query = User::query();

      // 検索ワードをクエリに追加（検索ワード部分一致）
      if(isset($req['search_word']) && $req['search_word'] != null){
        $word = $req['search_word'];
        $query->where('name', 'like', "%$word%");
      }

      // 検索からログインユーザー自身のデータを除外するクエリ追加
      $query->where('id', '<>', $current_uid);

      // 作成したクエリを実行
      $res = $query->get();

      // 検索結果をリターン
      return $res;
    }


    /**
    * Httpリクエストフォームから検索キーを抽出するfunction
    *
    * @param array $request_form            Httpリクエスト内の検索キーオブジェクト
    * @param string $type                   プラン、スポット、ユーザーの別
    * @return array $req                    検索結果データを格納した配列
    */
    public function getSearchKey($request_form, $type)
    {
      $search_key = [];

      switch ($type) {
        case 'plan':
          // 検索キーワードの取得処理（フリーワード / 交通手段 / 所用日数 / 住所）
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
          break;

        case 'spot':
        // 検索キーワードの取得処理（フリーワード / 滞在時間 / 住所）
          if(isset($request_form['search_word'])){
            $search_key['search_word'] = $request_form['search_word'];
          }
          if(isset($request_form['stay'])){
            $search_key['stay'] = $request_form['stay'];
          }
          if(isset($request_form['address'])){
            $search_key['address'] = $request_form['address'];
          }
          break;

        case 'user':
          // 検索キーワードの取得処理（フリーワード）
          if(isset($request_form['search_word'])){
            $search_key['search_word'] = $request_form['search_word'];
          }
          break;
      }

      return $search_key;
    }


    /**
    * 検索結果数を元にViewにリターンするレスポンス完成処理function
    *
    * @param array $request_form            Httpリクエストform
    * @param array $response                完成前レスポンス
    * @param array $search_key              検索キーワード用配列
    * @param string $type                   プラン、スポット、ユーザーの別
    */
    public function completeResponse($request_form, $response, $search_key, $type)
    {
      $next_index = $request_form['next_index'];

      // 検索結果なしの場合、リターン
      if(count($response) == 0 || $response[0] == []) {
        return ([
          'result' => 'no_result',
          'search_key' => $search_key,
        ]);
      // 非同期処理時のリターン
      }else{
        return ([
          'response' => $response[$next_index],
          'total_page' => count($response),
          'search_key' => $search_key,
          'result' => $type,
        ]);
      }
    }


    /**
    * 配列要素追加関数
    *
    * @param array $array                   追加先配列
    * @param array $target                  追加対象配列
    * @return array $array                  追加後配列
    */
    public function array_push_eachItem($array, $target)
    {
      foreach($target as $item){
        array_push($array, $item);
      }

      return $array;
    }
}
