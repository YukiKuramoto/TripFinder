<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostRequest;
use App\Http\Requests\SpotEditRequest;
use App\Http\Requests\PlanEditRequest;
use \Symfony\Component\HttpFoundation\Response;
use Validator;
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

class PostController extends Controller
{
    //
    public function show()
    {
        return view('post.index', ['type' => 'post']);
    }

    public function create(PostRequest $request)
    {
      // dd(json_decode($request->all()['day_0_spot_0_image_0']));
        // ユーザーID特定
        $uid = Auth::id();
        DB::beginTransaction();

        try {
          // リクエスト内容全体を取得
          $request_body = $request->all();
          // dd($request->all());
          $planOutline = $request_body['planOutline'];
          $dayInfo = $request_body['dayInfo'];
          // イメージデータのみにするため不要キー削除
          unset(
            $request_body['planOutline'],
            $request_body['request'],
            $request_body['dayInfo']
          );
          $request_images = $request_body;


          // キーに含まれるDay情報、Spot情報をもとに画像データ配列化
          foreach ($request_images as $key => $image_item) {
            [$d_index, $s_index, $i_index] = explode('_', $key);
            // 画像データを$image_array['dayXXspotXX']の配列化
            $array_key = 'day' . $d_index . 'spot' . $s_index;

            if(!isset($image_array[$array_key])){
              $image_array[$array_key] = [];
            }
            array_push($image_array[$array_key], $image_item);
          }


          // プランアウトライン保存
          $plan = new Plan;
          $plan_tags = $planOutline['plan_tag'];
          $planOutline += [
            'user_id' => $uid,
            'plan_duration' => end($dayInfo)['day_count'] + 1
          ];
          unset($planOutline['plan_tag'], $planOutline['displayStyle']);
          $plan->fill($planOutline)->save();
          // プランタグ保存
          $plan_tag = explode(',', $plan_tags);
          $this->registerTag($plan_tag, $plan->id, 'plan');

          //スポットデータ保存
          foreach ($dayInfo as $d_index => $day_form) {
            foreach ($day_form['spotInfo'] as $s_index => $spot_form) {
              // dd($s_index);
              // 登録必要データをセット
              $spot_form += ['user_id' => $uid];
              $spot_form += ['plan_id' => $plan->id];
              $spot_form['spot_day'] = $spot_form['spot_day'] + 1;
              // タグの取り出し
              $spot_tags = $spot_form['spot_tag'];
              $spot_link = $spot_form['spot_link'];

              // 不要キー削除
              unset(
                $spot_form['spot_count'],
                $spot_form['spot_display'],
                $spot_form['spot_image_preview'],
                $spot_form['spot_image'],
                $spot_form['spot_tag'],
                $spot_form['spot_link'],
                $spot_form['spot_accordion_display'],
              );

              $spot = new Spot;
              $spot->fill($spot_form)->save();
              // スポットタグデータ保存処理
              $spot_tag = explode(',', $spot_tags);
              $this->registerTag($spot_tag, $spot->id, 'spot');


              //スポットリンク保存処理
              foreach($spot_link as $key => $link){
                if(count($link) != 0){
                  $new_link = new Link;
                  $new_link->spot_id = $spot->id;
                  $new_link->link_title = $link['link_title'];
                  // dd(1);
                  $new_link->link_url = $link['link_url'];
                  $new_link->save();
                }
              }

              // スポット画像データ保存
              // dd($image_array);
              foreach ($image_array['day' . $d_index . 'spot' . $s_index] as $image) {
                $new_image = new Image;
                $path = Storage::disk('s3')->putFile('/', $image, 'public');
                $new_image->image_path = Storage::disk('s3')->url($path);
                $new_image->spot_id = $spot->id;
                $new_image->save();
              }
            }
          }
          // データをcommit
          DB::commit();
        } catch (\Exception $e){
          // エラーの場合、ロールバック
          DB::rollback();
          //
          return response()->json('失敗しました', Response::HTTP_NOT_FOUND);
          // logs()->error('エラーテスト');
          return response()->json(['status' => 500]);
        }
        return response()->json(['response_code' => Response::HTTP_CREATED, 'plan_id' => $plan->id]);
        // return response()->json(['status' => 200]);
    }


    public function registerTag($tag_array, $post_id, $post_type)
    {
      foreach ($tag_array as $tag_name) {
        if($tag_name == ""){
          break;
        }
        // タグのDB登録処理
        $tag = new Tag;
        // リクエスト内タグ名でDB検索
        $tag_db = Tag::where('name', $tag_name)->first();
        // NULLの場合はタグをDBに新規登録
        if($tag_db == null){
          $tag_form = array('name' => $tag_name);
          $tag->fill($tag_form)->save();
          $tag_id = $tag->id;
        }else{
          $tag_id = $tag_db->id;
        }

        // Postタイプによって中間テーブル登録先変更
        switch ($post_type) {
          case 'plan':
            $post_tag = new PlanTag;
            $post_tag_form = array('plan_id' => $post_id, 'tag_id' => $tag_id);
            break;

          case 'spot':
            $post_tag = new SpotTag;
            $post_tag_form = array('spot_id' => $post_id, 'tag_id' => $tag_id);
            break;

          default:
            echo "該当なし";
            break;
        }

        // DB登録
        $post_tag->fill($post_tag_form)->save();
      }
    }


    public function edit($plan_id)
    {
        $plan = Plan::find($plan_id);
        $post_uid = $plan->user['id'];
        $current_uid = Auth::id();
        // ユーザー確認
        if($this->checkLoginStatus($current_uid, $post_uid) == false){
          return redirect()->action('MypageController@index', ['user_id' => $current_uid]);
        }

        $plan_tag = "";
        foreach($plan->tags as $tag){
          $plan_tag = $plan_tag . $tag->name . ",";
        }
        $plan->plan_tag = $plan_tag;

        foreach ($plan->spots as $spot) {
          $spot->images;
          $spot_tag = "";
          foreach($spot->tags as $tag){
            $spot_tag = $spot_tag . $tag->name . ",";
          }
          $spot->spot_tag = $spot_tag;
        }
        // dd($plan->plan_title);
        return view('post.index', ['plan' => $plan, 'spot' => $plan->spots, 'type' => 'planedit']);
    }


    public function spotedit($spot_id)
    {
      $spot = Spot::find($spot_id);
      $spot->images;
      $spot['spot_link'] = $spot->links;
      $spot_tag = "";

      //ユーザー特定
      $current_uid = Auth::id();
      $post_uid = $spot->user['id'];

      // ユーザー確認
      if($this->checkLoginStatus($current_uid, $post_uid) == false){
        return redirect()->action('MypageController@index', ['user_id' => $current_uid]);
      }

      foreach($spot->tags as $tag){
        $spot_tag = $spot_tag . $tag->name . ",";
      }
      $spot->spot_tag = $spot_tag;

      return view('post.index', ['spot' => $spot, 'type' => 'spotedit']);
    }


    public function planUpdate(PlanEditRequest $request, $plan_id)
    {

      DB::beginTransaction();

      try{
        $request_body = $request->all();
        $request_form = $request_body['planOutline'];

        $plan_form = [];
        $tag = $request_form['plan_tag'];

        // プラン特定
        $plan = Plan::find($plan_id);

        //ユーザー特定
        $current_uid = Auth::id();
        $post_uid = $plan->user['id'];

        // ユーザー確認
        if($this->checkLoginStatus($current_uid, $post_uid) == false){
          return redirect()->action('MypageController@index', ['user_id' => $current_uid]);
        }

        // 既存情報削除
        PlanTag::where('plan_id', $plan_id)->delete();

        // 必要情報セット
        $plan_form += ['user_id' => $request_form['user_id']];
        $plan_form += ['plan_title' => $request_form['plan_title']];
        $plan_form += ['main_transportation' => $request_form['main_transportation']];
        $plan_form += ['plan_duration' => $request_form['plan_duration']];
        $plan_form += ['plan_information' => $request_form['plan_information']];

        $plan->fill($plan_form)->save();

        $plan_tag = explode(',', $tag);
        $this->registerTag($plan_tag, $plan_id, 'plan');

        // データをcommit
        DB::commit();
      } catch (\Exception $e){
        // エラーの場合、ロールバック
        DB::rollback();
        //
        return response()->json('失敗しました', Response::HTTP_NOT_FOUND);
      }
      return response()->json(['response_code' => Response::HTTP_CREATED, 'plan_id' => $plan_id]);

    }


    public function spotUpdate(SpotEditRequest $request, $spot_id)
    {

      DB::beginTransaction();

      try{
        $request_body = $request->all();
        $request_form = $request_body['dayInfo'];
        unset($request_body['dayInfo'], $request_body['request']);

        $image_array = [];
        $spot_form = [];
        $plan_id = $request_form['plan_id'];
        $tag = $request_form['spot_tag'];
        $link_array =  $request_form['spot_link'];

        // スポット特定
        $spot = Spot::find($spot_id);
        $images = $spot->images;
        $links = $spot->links;

        //ユーザー特定
        $current_uid = Auth::id();
        $post_uid = $spot->user['id'];

        // ユーザー確認
        if($this->checkLoginStatus($current_uid, $post_uid) == false){
          return redirect()->action('MypageController@index', ['user_id' => $current_uid]);
        }

        // 既存情報削除
        foreach($images as $image) {
          Image::find($image->id)->delete();
        }

        foreach($links as $link) {
          Link::find($link->id)->delete();
        }

        SpotTag::where('spot_id', $spot_id)->delete();

        // DB登録前処理開始
        // 写真配列作成
        foreach ($request_body as $key => $image_item) {
          array_push($image_array, $image_item);
        }

        // 必要情報セット
        $spot_form += ['user_id' => $request_form['user_id']];
        $spot_form += ['plan_id' => $request_form['plan_id']];
        $spot_form += ['spot_title' => $request_form['spot_title']];
        $spot_form += ['spot_duration' => $request_form['spot_duration']];
        $spot_form += ['spot_address' => $request_form['spot_address']];
        $spot_form += ['spot_day' => $request_form['spot_day']];
        $spot_form += ['spot_information' => $request_form['spot_information']];

        $spot->fill($spot_form)->save();

        $spot_tag = explode(',', $tag);
        $this->registerTag($spot_tag, $spot_id, 'spot');

        // dd($link_array);

        //スポットリンク保存処理
        foreach($link_array as $key => $link){
          if(count($link) != 0){
            $new_link = new Link;
            $new_link->spot_id = $spot->id;
            $new_link->link_title = $link['link_title'];
            $new_link->link_url = $link['link_url'];
            $new_link->save();
          }
        }

        foreach ($image_array as $image) {
          $new_image = new Image;
          $path = Storage::disk('s3')->putFile('/', $image, 'public');
          $new_image->image_path = Storage::disk('s3')->url($path);
          $new_image->spot_id = $spot_id;
          $new_image->save();
        }
        // データをcommit
        DB::commit();
      } catch (\Exception $e){
        // エラーの場合、ロールバック
        DB::rollback();
        //
        return response()->json('失敗しました', Response::HTTP_NOT_FOUND);
      }
      return response()->json(['response_code' => Response::HTTP_CREATED, 'plan_id' => $plan_id]);
    }


    public function delete($plan_id)
    {
      DB::beginTransaction();
      try {
        $plan = Plan::find($plan_id);
        $spots = $plan->spots;
        $plan_tags = $plan->tags;
        $plan_favs = $plan->favs;
        $post_uid = $plan->user['id'];
        $current_uid = Auth::id();

        foreach ($spots as $spot) {
          $images = $spot->images;
          $comments = $spot->comments;
          $spot_favs = $spot->favs;
          $spot_tags = $spot->tags;
        }

        // ユーザー確認
        if($this->checkLoginStatus($current_uid, $post_uid) == false){
          return redirect()->action('MypageController@index', ['user_id' => $current_uid]);
        }

        Plan::find($plan->id)->delete();

        foreach($plan_tags as $tag) {
          PlanTag::where('plan_id', $tag->plan_id)->delete();
        }

        foreach($plan_favs as $fav) {
          FavPlan::find($fav->id)->delete();
        }

        foreach($spots as $spot) {
          Spot::find($spot->id)->delete();
        }

        foreach($images as $image) {
          Image::find($image->id)->delete();
        }

        foreach($comments as $comment) {
          SpotComment::find($comment->id)->delete();
        }

        foreach($spot_favs as $fav) {
          FavSpot::find($fav->id)->delete();
        }

        foreach($spot_tags as $tag) {
          SpotTag::where('spot_id', $tag->spot_id)->delete();
        }

        DB::commit();
        $uid = Auth::id();
        return redirect()->action('MypageController@index', ['user_id' => $uid]);

      } catch(\Exception $e){
        DB::rollback();
        dd($e);
      }
    }

    public function del($class, $items)
    {
      foreach ($items as $item) {
        $target = $class::find($item->id);
      }
    }

}
