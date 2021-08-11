<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Plan;
use App\Spot;
use App\Tag;
use App\User;

class PostController extends Controller
{
    //
    public function show()
    {
        return view('post.index');
    }
    
    public function create(Request $request)
    {
        $uid = Auth::id();;
        // dd($uid);
        $form = $request->all();
        dd($form);
        $spot_count = $form->spot_count;
        
    }
}
