<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostRequest;
use App\Http\Requests\SpotEditRequest;
use App\Http\Requests\PlanEditRequest;
use App\Services\DBRegisterService;
use \Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use Validator;
use App\Plan;
use App\Spot;
use App\User;


class PostController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Post Controller
  |--------------------------------------------------------------------------
  |
  | プラン・スポット投稿保存用コントローラー
  | ビジネスロジックはDBRegisterService内に定義したFunctionによって実行
  |
  | index          : 投稿画面用ビュー表示
  | create         : プラン・スポット投稿データ新規保存処理
  | planEditIndex  : 投稿済みPlanOutline編集画面用ビュー表示
  | spotEditIndex  : 投稿済みSpot編集画面用ビュー表示
  | planUpdate     : 投稿済みPlanOutline更新処理
  | spotUpdate     : 投稿済みSpot更新処理
  | delete         : プラン・スポット削除処理
  |
  */


    private $DBRegisterService;


    public function __construct(DBRegisterService $DB_service)
    {
      $this->DBRegisterService = $DB_service;
    }


    /**
    * プラン・スポット投稿用ビュー表示用function
    *
    * @return Illuminate\Contracts\Support\Renderable        プラン投稿用ビュー
    */
    public function index()
    {
      return view('post.index', ['type' => 'post']);
    }


    /**
    * プラン・スポット新規作成function
    * ユーザーが投稿したプラン・スポット内容をDBに保存する
    *
    * @param App\Http\Requests\PostRequest $request           Httpリクエスト
    * @return \Symfony\Component\HttpFoundation\Response      実行結果レスポンスコード
    */
    public function create(PostRequest $request)
    {
      DB::beginTransaction();

      try {
        // ユーザーID特定
        $uid = Auth::id();
        // リクエスト内ベース情報取得
        $request_body = $request->all();
        // プランアウトライン情報取得
        $planOutline = $request_body['planOutline'];
        // プラン情報取得
        $dayInfo = $request_body['dayInfo'];
        // プランタグ切り出し
        $plan_tags = $planOutline['plan_tag'];

        // スポット画像配列作成
        $image_array = $this->DBRegisterService->createImageArray('create', $request_body);

        // プランアウトライン保存処理
        $plan = $this->DBRegisterService->registerPlanOutline('create', $planOutline, $dayInfo, $uid, '');
        // プランタグ保存処理
        $this->DBRegisterService->registerTag($plan_tags, $plan->id, 'plan');

        //スポット関連情報保存処理
        foreach ($dayInfo as $d_index => $day_form) {
          foreach ($day_form['spotInfo'] as $s_index => $spot_form) {

            // 画像連想配列のキーを作成
            $image_key = 'day' . $d_index . 'spot' . $s_index;
            // タグの取り出し
            $spot_tags = $spot_form['spot_tag'];
            $spot_link = $spot_form['spot_link'];

            // スポット保存処理
            $spot = $this->DBRegisterService->registerSpot('create', $spot_form, $plan, $uid, '');
            // スポットタグ保存処理
            $this->DBRegisterService->registerTag($spot_tags, $spot->id, 'spot');
            // スポットリンク保存処理
            $this->DBRegisterService->registerLink($spot_link, $spot->id);
            // スポット画像保存処理
            $this->DBRegisterService->registerImage($image_array[$image_key], $spot->id);

          }
        }
        // データをcommit
        DB::commit();

      } catch (\Exception $e){
        // エラーの場合、ロールバック
        DB::rollback();
        // エラーレスポンス
        return response()->json('失敗しました', Response::HTTP_NOT_FOUND);
      }
      // 実行完了レスポンス
      return response()->json(['response_code' => Response::HTTP_CREATED, 'plan_id' => $plan->id]);
    }


    /**
    * プランアウトライン編集画面表示用function
    *
    * @param string $plan_id                                  プランID
    * @return Illuminate\Contracts\Support\Renderable         PlanOutline編集画面
    */
    public function planEditIndex($plan_id)
    {
      //ユーザー・プランアウトライン特定
      $plan = Plan::find($plan_id);
      $current_uid = Auth::id();
      $post_uid = $plan->user['id'];

      // ユーザー確認
      if($this->checkLoginStatus($current_uid, $post_uid) == false){
        return redirect()->action('Main\MypageController@index', ['user_id' => $current_uid]);
      }

      // Vue.js表示用に関連情報取得（タグは区切りに変換）
      $plan->plan_tag = $this->mergeTags($plan);

      return view('post.index', ['plan' => $plan, 'spot' => $plan->spots, 'type' => 'planedit']);
    }


    /**
    * スポット編集画面表示用function
    *
    * @param string $spot_id                                  スポットID
    * @return Illuminate\Contracts\Support\Renderable         スポット編集画面
    */
    public function spotEditIndex($spot_id)
    {
      //ユーザー・スポット特定
      $spot = Spot::find($spot_id);
      $current_uid = Auth::id();
      $post_uid = $spot->user['id'];

      // ユーザー確認
      if($this->checkLoginStatus($current_uid, $post_uid) == false){
        return redirect()->action('Main\MypageController@index', ['user_id' => $current_uid]);
      }

      // Vue.js表示用に関連情報取得（タグは区切りに変換）
      $spot->images;
      $spot['spot_link'] = $spot->links;
      $spot->spot_tag = $this->mergeTags($spot);

      return view('post.index', ['spot' => $spot, 'type' => 'spotedit']);
    }


    /**
    * プランアウトライン更新用function
    *
    * @param App\Http\Requests\PostRequest $request           Httpリクエスト
    * @return \Symfony\Component\HttpFoundation\Response      実行結果レスポンスコード
    */
    public function planUpdate(PlanEditRequest $request, $plan_id)
    {

      DB::beginTransaction();

      try{
        // リクエスト内ベース情報取得
        $request_body = $request->all();
        $planOutline = $request_body['planOutline'];

        // タグ情報取得
        $tag = $planOutline['plan_tag'];

        // 情報更新前に関連情報をInit
        $this->DBRegisterService->initRelationalInfo('planOutline', $plan_id);

        // スポット保存処理
        $plan = $this->DBRegisterService->registerPlanOutline('update', $planOutline,'','', $plan_id);
        // プランタグ保存処理
        $this->DBRegisterService->registerTag($tag, $plan_id, 'plan');

        // データをcommit
        DB::commit();

      } catch (\Exception $e){
        // エラーの場合、ロールバック
        DB::rollback();
        //エラーレスポンス
        return response()->json('失敗しました', Response::HTTP_NOT_FOUND);
      }
      // 実行完了レスポンス
      return response()->json(['response_code' => Response::HTTP_CREATED, 'plan_id' => $plan_id]);
    }


    /**
    * スポット更新用function
    *
    * @param App\Http\Requests\PostRequest $request           Httpリクエスト
    * @return \Symfony\Component\HttpFoundation\Response      実行結果レスポンスコード
    */
    public function spotUpdate(SpotEditRequest $request, $spot_id)
    {

      DB::beginTransaction();

      try{
        // リクエスト内ベース情報取得
        $request_body = $request->all();
        $dayInfo = $request_body['dayInfo'];

        // タグ情報取得
        $tag = $dayInfo['spot_tag'];
        // リンク情報取得
        $link =  $dayInfo['spot_link'];
        // 画像配列取得
        $image_array = $this->DBRegisterService->createImageArray('update', $request_body);

        // 情報更新前に関連情報をInit
        $this->DBRegisterService->initRelationalInfo('spot', $spot_id);

        // スポット保存処理
        $spot = $this->DBRegisterService->registerSpot('update', $dayInfo, '', '', $spot_id);
        // スポットタグ保存処理
        $this->DBRegisterService->registerTag($tag, $spot->id, 'spot');
        // スポットリンク保存処理
        $this->DBRegisterService->registerLink($link, $spot->id);
        // スポット画像保存処理
        $this->DBRegisterService->registerImage($image_array, $spot->id);

        // データをcommit
        DB::commit();

      } catch (\Exception $e){
        // エラーの場合、ロールバック
        DB::rollback();
        //エラーレスポンス
        return response()->json('失敗しました', Response::HTTP_NOT_FOUND);
      }
      // 実行完了レスポンス
      return response()->json(['response_code' => Response::HTTP_CREATED, 'plan_id' => $plan_id]);
    }


    /**
    * 投稿済みプラン削除用function
    *
    * @param string $spot_id                                  プランID
    * @return Illuminate\Http\RedirectResponse　　　　      　　MyPage画面ビュー
    */
    public function delete($plan_id)
    {

      DB::beginTransaction();

      try {
        // プラン・投稿ユーザー・ログインユーザー取得
        $plan = Plan::find($plan_id);
        $post_uid = $plan->user['id'];
        $current_uid = Auth::id();

        // ユーザー確認
        if($this->checkLoginStatus($current_uid, $post_uid) == false){
          return redirect()->action('Main\MypageController@index', ['user_id' => $current_uid]);
        }

        // プラン削除（関連情報は外部キー制約により削除）
        Plan::find($plan->id)->delete();

        // データをcommit
        DB::commit();

      } catch(\Exception $e){
        // エラーの場合、ロールバック
        DB::rollback();
        //エラーレスポンス
        return response()->json('失敗しました', Response::HTTP_NOT_FOUND);
      }
      // 実行完了レスポンス
      return redirect()->action('Main\MypageController@index', ['user_id' => $current_uid]);
    }



}
