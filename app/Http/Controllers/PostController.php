<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostRequest;
use Validator;
use App\Plan;
use App\Spot;
use App\Tag;
use App\Image;
use App\PlanTag;
use App\SpotTag;
use App\User;
use Storage;

class PostController extends Controller
{
    //
    public function show()
    {
        return view('post.index');
    }

    public function create(PostRequest $request)
    {
        // リクエストから投稿用データ取得
        $spot_form = $request->spot;
        $plan_form = $request->plan[0];
        $uid = Auth::id();
        dd(1);
        // プラン処理
        $plan = new Plan;
        $plan_form += array('user_id' => $uid);
        $plan_form += array('plan_duration' => end($spot_form)['spot_day']);
        // タグデータ取出し
        $plan_tag = explode(",", trim($plan_form['plan_tag']));
        unset($plan_form['plan_tag']);
        // DB登録
        $plan->fill($plan_form)->save();
        $this->registerTag($plan_tag, $plan->id, 'plan');


        foreach ($spot_form as $item) {
          // スポット処理
          $spot = new Spot;
          // ID追加
          $item += array('user_id' => $uid);
          $item += array('plan_id' => $plan->id);

          // タグデータ,画像データ取出し
          $spot_tag = explode(',', trim($item['spot_tag']));
          $spot_images = $item['spot_image'];

          // DB登録
          unset($item['spot_tag'], $item['spot_day'], $item['spot_image']);
          $spot->fill($item);
          $spot->save();

          //画像データ保存
          foreach($spot_images as $spot_image){
            $image = new Image;
            $path = Storage::disk('s3')->putFile('/', $spot_image, 'public');
            $image->image_path = Storage::disk('s3')->url($path);
            $image->spot_id = $spot->id;
            $image->save();
          }

          $this->registerTag($spot_tag, $spot->id, 'spot');
        }

        $user = User::find($uid);
        dd('OK');
        return view('mypage.index', ['user' => $user]);
    }


    public function registerTag($tag_array, $post_id, $post_type)
    {
      foreach ($tag_array as $tag_name) {
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




    public function index()
    {
        $user = User::find(2);
        dd($user->plans);
        $plan_reqs = Plan::find(11);
        $spot_info = $plan_reqs->spots;
        // dd($spot_info[0]->spot_title);

        $tags = $plan_reqs->tags;
        // dd($tags[0]->name);

        $spot_reqs = Spot::find(17);
        // dd($spot_reqs);
        $tags = $spot_reqs->tags;
        dd($tags);
        $plan_info = $spot_reqs->plans;

        dd($plan_info->plan_title);
    }
}
