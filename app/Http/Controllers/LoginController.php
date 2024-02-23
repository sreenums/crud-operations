<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginForm()
    {
        return view('login.user-login');
    }

    public function login(Request $request)
    {
        //var_dump(request()->all());

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('/home');
        }

        return redirect()->back()->withInput($request->only('email'))->withErrors(['loginError' => 'Invalid email or password']);
        //return view('/');
    }
}
