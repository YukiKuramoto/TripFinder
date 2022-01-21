<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use App\Plan;
use App\Spot;
use App\SpotComment;

class CommentController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Comment Controller
  |--------------------------------------------------------------------------
  |
  | 投稿されたスポットに対するユーザーのコメントの表示、登録削除を行うコントローラー
  | index   : コメント投稿用ビュー表示
  | create  : コメント登録
  | show    : コメント一覧画面表示
  | delete  : コメント削除
  |
  */


   /**
   * コメント投稿用ビュー表示用function
   *
   * @param Illuminate\Http\Request $request              Httpリクエスト
   * @return Illuminate\Contracts\Support\Renderable      コメント投稿用ビュー
   */
    public function index(Request $request)
    {
      // スポット情報からコメント投稿対象スポット、プランを特定
      $user = Auth::id();
      // dd($request->all());
      $spot = Spot::find($request->all()['spot_id']);
      $plan = $spot->plans;

      return view('comment.index', ['user' => $user, 'spot' => $spot, 'plan' => $plan]);
    }


   /**
   * コメント登録用function
   *
   * @param Illuminate\Http\Request $request              Httpリクエスト
   * @return Illuminate\Contracts\Support\Renderable      コメント一覧表示用ビュー
   */
    public function create(Request $request)
    {
      $comment = new SpotComment;
      $comment_form = $request->all();
      // dd($comment_form);
      $spot = Spot::with('comments')->find($comment_form['spot_id']);
      $plan = Plan::find($comment_form['plan_id']);

      // DB登録処理
      $comment_form = $request->all();
      unset($comment_form['_token']);
      unset($comment_form['plan_id']);
      $comment->fill($comment_form)->save();

      return view('comment.show', ['plan' => $plan, 'spot' => $spot]);
    }


   /**
   * コメント一覧表示用function
   *
   * @param Illuminate\Http\Request $request              Httpリクエスト
   * @return Illuminate\Contracts\Support\Renderable      コメント一覧表示用ビュー
   */
    public function show(Request $request)
    {
      $request_form = $request->all();
      $plan = Plan::find($request_form['plan_id']);
      $spot = Spot::with('comments.user')->with('user')->find($request_form['spot_id']);

      return view('comment.show', ['plan' => $plan, 'spot' => $spot]);
    }


   /**
   * コメント削除用function
   *
   * @param Illuminate\Http\Request $request              Httpリクエスト
   * @return Illuminate\Http\RedirectResponse　　　　      コメント削除対象プラン画面ビュー
   */
    public function delete(Request $request)
    {
      $current_uid = Auth::id();
      $comment = SpotComment::find($request->all()['comment_id']);
      $post_uid = $comment->user['id'];

      // ユーザー確認
      if($this->checkLoginStatus($current_uid, $post_uid) == false){
        return redirect()->action('MypageController@index', ['user_id' => $current_uid]);
      }
      // DB削除処理
      $comment->delete();

      return redirect()->action('Main\PlanpageController@index', ['plan_id' => $request->all()['plan_id']]);
    }
}
