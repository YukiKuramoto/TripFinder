<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Spot;
use App\SpotComment;

class CommentController extends Controller
{
    //
    public function index(Request $request)
    {
      $spot = Spot::find($request->spotId);
      $user = Auth::id();

      return view('comment.index', ['user' => $user, 'spot' => $spot]);
    }

    public function create(Request $request)
    {
      $comment = new SpotComment;
      $comment_form = $request->all();
      unset($comment_form['_token']);
      $comment->fill($comment_form)->save();
      dd(1);
    }
}
