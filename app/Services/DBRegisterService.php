<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Plan;
use App\Spot;
use App\Tag;
use App\Image;
use App\Link;
use App\PlanTag;
use App\SpotTag;
use App\User;
use App\FavPlan;
use App\FavSpot;
use App\SpotComment;
use Storage;

class DBRegisterService
{
  /*
  |--------------------------------------------------------------------------
  | DBRegister Service
  |--------------------------------------------------------------------------
  |
  | データベース登録・更新・削除用ビジネスロジック
  |
  | ユーザーの新規投稿の保存、投稿済みデータの更新、投稿済みデータの削除
  | 及びそれら処理のためのビューを返す処理を実行する
  |
  | registerPlanOutline   : プランアウトラインをDBに新規保存・更新
  | registerSpot          : スポットデータをDBに新規保存・更新
  | registerTag           : プラン・スポットタグデータをDBに新規保存
  | registerLink  　　　   : スポットリンクデータをDBに新規保存
  | registerImage  　　　  : 画像URLデータをDBに新規保存
  | createImageArray      : DB保存用に画像配列作成処理
  | initRelationalInfo    : DB保存用に関連情報初期化処理
  |
  */


    /**
    * プランアウトラインDB新規保存・更新用function
    * $typeを元に新規保存か更新か判断し、配列内項目調整後DBに保存実行
    *
    * @param string $type                   新規保存・更新の別
    * @param array $planOutline             プランアウトラインデータ格納配列
    * @param array $dayInfo Optional        スポットデータ格納配列（新規保存用）
    * @param string $uid Optional           投稿ユーザーID（新規保存用）
    * @param string $plan_id Optional       更新対象プランID（更新用）
    * @return object $req                    保存実行後プランアウトラインオブジェクト
    */
    public function registerPlanOutline($type, $planOutline, $dayInfo = null, $uid = null, $plan_id = null)
    {

      switch ($type) {
        // 保存対象データ配列に必要項目追加・保存用プランインスタンス作成
        case 'create':
          $planOutline += ['user_id' => $uid, 'plan_duration' => end($dayInfo)['day_count'] + 1];
          $plan = new Plan;
          break;

        // 更新対象のプランの特定・取得
        case 'update':
          $plan = Plan::find($plan_id);
          break;
      }

      // プランアウトライン不要項目削除
      unset(
        $planOutline['plan_tag'],
        $planOutline['displayStyle'],
        $planOutline['user'],
        $planOutline['tags'],
        $planOutline['spots']
      );

      // 保存処理実行
      $plan->fill($planOutline)->save();

      return $plan;
    }


    /**
    * スポットデータDB新規保存・更新用function
    * $typeを元に新規保存か更新か判断し、配列内項目調整後DBに保存実行
    *
    * @param string $type                   新規保存・更新の別
    * @param array $spot_form               スポットデータ格納配列
    * @param array $plan Optional           リレーション先plan配列（新規保存用）
    * @param string $uid Optional           投稿ユーザーID（新規保存用）
    * @param string $spot_id Optional       更新対象スポットID（更新用）
    * @return object $req                   保存実行後スポットデータオブジェクト
    */
    public function registerSpot($type, $spot_form, $plan = null, $uid = null, $spot_id = null)
    {
      switch ($type) {
        // 登録必要データをセットし、スポット保存用インスタンス作成
        case 'create':
          $spot = new Spot;
          $spot_form += ['user_id' => $uid];
          $spot_form += ['plan_id' => $plan->id];
          $spot_form['spot_day'] = $spot_form['spot_day'] + 1;
          break;

        // 更新対象のスポットを取得
        case 'update':
          $spot = Spot::find($spot_id);
          break;
      }

      // 不要キー削除
      unset(
        $spot_form['spot_count'],
        $spot_form['spot_display'],
        $spot_form['spot_image_preview'],
        $spot_form['spot_image'],
        $spot_form['spot_tag'],
        $spot_form['spot_link'],
        $spot_form['spot_accordion_display'],
        $spot_form['user'],
        $spot_form['images'],
        $spot_form['links'],
        $spot_form['tags'],
      );

      // 保存処理実行
      $spot->fill($spot_form)->save();

      return $spot;
    }


    /**
    * タグデータDB新規保存用function
    * カンマ区切りタグデータからタグデータを取得し、DBに同一名称タグがなければタグデータを新規登録
    * 登録後、プラン・スポットとの中間テーブルにリレーションを保存
    *
    * @param string $tag_data               カンマ区切りタグデータ
    * @param string $post_id                投稿ユーザーID
    * @param string $type                   プラン、スポットの別
    * @return void
    */
    public function registerTag($tag_data, $post_id, $type)
    {
      // カンマ区切りのタグデータを各タグに分割し配列作成
      $tag_array = explode(',', $tag_data);

      // 配列繰り返し処理
      foreach ($tag_array as $tag_name) {

        if($tag_name == ""){
          break;
        }

        // DB登録用タグインスタンス作成
        $tag = new Tag;
        // 対象タグ名称をDB検索
        $tag_db = Tag::where('name', $tag_name)->first();

        // NULLの場合、タグをDBに新規登録し、新規登録したタグデータIDをセット
        if($tag_db == null){
          $tag_form = array('name' => $tag_name);
          $tag->fill($tag_form)->save();
          $tag_id = $tag->id;
        // NULLでない場合、検索でヒットしたタグデータIDをセット
        }else{
          $tag_id = $tag_db->id;
        }

        // Postタイプによって中間テーブル登録先変更
        switch ($type) {
          case 'plan':
            $post_tag = new PlanTag;
            // プランIDとタグIDのセットを配列化
            $post_tag_form = array('plan_id' => $post_id, 'tag_id' => $tag_id);
            break;

          case 'spot':
            $post_tag = new SpotTag;
            // スポットIDとタグIDのセットを配列化
            $post_tag_form = array('spot_id' => $post_id, 'tag_id' => $tag_id);
            break;
        }

        // プラン・スポットIDとタグID配列を中間テーブルに保存
        $post_tag->fill($post_tag_form)->save();
      }
    }


    /**
    * リンクデータDB新規保存用function
    *
    * @param array $spot_link               リンクデータ格納配列
    * @param string spot_id                 リレーション先スポットID
    * @return void
    */
    public function registerLink($spot_link, $spot_id)
    {
      foreach($spot_link as $key => $link){

        if(count($link) != 0){
          // リンクデータ保存用インスタンス作成
          $new_link = new Link;
          // リレーション先スポットID・リンクタイトル・リンクURLをセット
          $new_link->spot_id = $spot_id;
          $new_link->link_title = $link['link_title'];
          $new_link->link_url = $link['link_url'];

          $new_link->save();
        }
      }
    }


    /**
    * 画像データDB新規保存用function
    *
    * @param array $image_array             画像データ格納配列
    * @param string spot_id                 リレーション先スポットID
    * @return void
    */
    public function registerImage($image_array, $spot_id)
    {
      foreach ($image_array as $image) {

        // 画像保存用インスタンス作成
        $new_image = new Image;
        // AWS S3に画像データ保存
        $path = Storage::disk('s3')->putFile('/', $image, 'public');
        // DB保存用URLを取得し、リレーション先スポットIDをセット
        $new_image->image_path = Storage::disk('s3')->url($path);
        $new_image->spot_id = $spot_id;

        $new_image->save();
      }
    }


    /**
    * 画像データ配列化function
    * 画像更新の場合、不要キー削除処理、新規保存の場合、以下の処理
    *
    * ※ AxiosでFormDataに画像情報を格納し、サーバーへ送信する際、
    * 　プラン、スポット情報に紐づく形式の多次元配列で送信できないため
    * 　各画像データがどのスポットデータと紐づくかという情報が
    * 「××（何日目）_××（何スポット目）_××（何番目）」という形式の連想配列のキーとして送られる。
    * 　このキー情報をもとにスポット毎の多次元配列に作成し直し、リターンする
    *
    * @param string $type                         新規保存、更新の別
    * @param array  $request_body                 リレーション先スポットID
    * @return array $request_images               DB保存用画像配列
    */
    public function createImageArray($type, $request_body)
    {
      // イメージデータのみにするため不要キー削除
      unset($request_body['planOutline'], $request_body['request'], $request_body['dayInfo']);
      $request_images = $request_body;

      if($type == 'create'){
        // キーに含まれるDay情報、Spot情報をもとに画像データ配列化
        foreach ($request_images as $key => $image_item) {
          [$d_index, $s_index, $i_index] = explode('_', $key);
          // 画像データを$image_array['dayXXspotXX']の配列化
          $array_key = 'day' . $d_index . 'spot' . $s_index;

          // 最初のインデックス番号で連想配列キーが存在していない場合、キーをセット
          if(!isset($image_array[$array_key])){
            $image_array[$array_key] = [];
          }
          array_push($image_array[$array_key], $image_item);
        }
        $request_images = $image_array;
      }

      return $request_images;
    }


    /**
    * 更新実行前、リレーション情報初期化処理
    *
    * @param string $type                         プランアウトライン、スポットの別
    * @param string $targetID                     リレーション先ID
    * @return void
    */
    public function initRelationalInfo($type, $targetID)
    {
      switch ($type) {

        case 'planOutline':
          // プランタグ既存情報削除
          PlanTag::where('plan_id', $targetID)->delete();
          break;

        case 'spot':
          // 対象スポットデータを取得
          $spot = Spot::find($targetID);
          $images = $spot->images;
          $links = $spot->links;

          // スポットタグ既存情報削除
          SpotTag::where('spot_id', $targetID)->delete();

          // 画像URL既存情報削除
          foreach($images as $image) {
            Image::find($image->id)->delete();
          }

          // リンク既存情報削除
          foreach($links as $link) {
            Link::find($link->id)->delete();
          }
          break;
      }
    }


}
