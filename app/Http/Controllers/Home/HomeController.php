<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }
    
    public function hoge(Request $request)
    {
        dd($request->all());
        return view('home.index');
    }
    
    public function test2(Request $request)
    {
        alert('OOO');
        dd($request->all());
        return view('home.index');
    }
}
