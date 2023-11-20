<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile()
    {
        if (Auth::check()) {
            return view('profile', ['user' => Auth::user()]);
        } else {
            return redirect()->route('login');
        }
    }
}