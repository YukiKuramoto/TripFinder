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
    //
    public function index(Request $request)
    {
      $spot = Spot::find($request->spotId);
      $plan = $spot->plans;
      $user = Auth::id();

      return view('comment.index', ['user' => $user, 'spot' => $spot, 'plan' => $plan]);
    }


    public function create(Request $request)
    {
      $comment = new SpotComment;
      $comment_form = $request->all();
      $spot = Spot::find($comment_form['spot_id']);
      $plan = Plan::find($comment_form['plan_id']);
      unset($comment_form['_token']);
      unset($comment_form['plan_id']);
      $comment->fill($comment_form)->save();

      foreach($spot->comments as $item){
        $user = User::find($item->user_id);
        $item->user_name = $user->name;
        $item->user_image = $user->image_path;
      }

      return view('comment.show', ['plan' => $plan, 'spot' => $spot]);
    }


    public function show(Request $request)
    {
      $request_form = $request->all();
      $plan = Plan::find($request_form['plan_id']);
      $spot = Spot::find($request_form['spot_id']);

      foreach($spot->comments as $item){
        $user = User::find($item->user_id);
        $item->user_name = $user->name;
        $item->user_image = $user->image_path;
      }

      return view('comment.show', ['plan' => $plan, 'spot' => $spot]);
    }


    public function delete(Request $request)
    {
      $comment = SpotComment::find($request->all()['comment_id']);
      $post_uid = $comment->user['id'];
      $current_uid = Auth::id();
      // ユーザー確認
      if($this->checkLoginStatus($current_uid, $post_uid) == false){
        return redirect()->action('MypageController@index', ['user_id' => $current_uid]);
      }

      $comment->delete();

      return redirect()->action('Main\PlanpageController@index', ['plan_id' => $request->all()['plan_id']]);
    }
}
