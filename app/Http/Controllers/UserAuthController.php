<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{

    public function authenticate(Request $request)
    {
        $form = $request->all();
        $email = $form["email"];
        $password = $form["password"];

        if(Auth::attempt(['email' => $email, 'password' => $password])){
            return redirect('home');
        }else{
            return redirect('home');
        }
    }

    public function logoutuser()
    {
        Auth::logout();
        return redirect('home');
    }

}
